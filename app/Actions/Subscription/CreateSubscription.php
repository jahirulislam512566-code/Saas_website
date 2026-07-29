<?php

namespace App\Actions\Subscription;

use App\Actions\BaseAction;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PaymentService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Cache;

class CreateSubscription extends BaseAction
{
    protected array $validationRules = [
        'user_id' => 'required|exists:users,id',
        'plan_id' => 'required|exists:plans,id',
        'status' => 'nullable|in:pending,active,cancelled,paused,expired',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
        'trial_ends_at' => 'nullable|date|after:start_date',
        'auto_renew' => 'nullable|boolean',
        'payment_method' => 'nullable|string',
        'amount' => 'nullable|numeric|min:0',
        'currency' => 'nullable|string|size:3',
        'activate' => 'nullable|boolean',
        'send_email' => 'nullable|boolean',
        'settings' => 'nullable|array',
        'metadata' => 'nullable|array',
    ];

    protected PaymentService $paymentService;
    protected EmailService $emailService;

    public function __construct(
        PaymentService $paymentService,
        EmailService $emailService
    ) {
        $this->paymentService = $paymentService;
        $this->emailService = $emailService;
    }

    public function execute(array $data): Subscription
    {
        $this->data = $data;
        $this->validate($data);

        $this->beginTransaction();

        try {
            $user = User::findOrFail($data['user_id']);
            $plan = Plan::findOrFail($data['plan_id']);

            // Check existing subscription
            $existingSubscription = $user->subscriptions()
                ->where('status', 'active')
                ->first();
            
            if ($existingSubscription && !($data['force_create'] ?? false)) {
                throw new ActionException(
                    'User already has an active subscription',
                    ['subscription_id' => $existingSubscription->id]
                );
            }

            // Cancel existing subscription if force create
            if ($existingSubscription && ($data['force_create'] ?? false)) {
                $this->cancelExistingSubscription($existingSubscription);
            }

            // Calculate dates
            $startDate = $data['start_date'] ?? now();
            $endDate = $data['end_date'] ?? $this->calculateEndDate($plan, $startDate);
            $trialEndsAt = $data['trial_ends_at'] ?? $this->calculateTrialEnd($plan);

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => $data['status'] ?? 'pending',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'trial_ends_at' => $trialEndsAt,
                'auto_renew' => $data['auto_renew'] ?? true,
                'payment_method' => $data['payment_method'] ?? null,
                'amount' => $data['amount'] ?? $plan->price,
                'currency' => $data['currency'] ?? $plan->currency ?? 'USD',
                'settings' => array_merge([
                    'created_by' => auth()->id(),
                    'created_at' => now()->toDateTimeString(),
                ], $data['settings'] ?? []),
                'metadata' => $data['metadata'] ?? [],
            ]);

            // Process payment if required
            if ($data['process_payment'] ?? false) {
                $payment = $this->paymentService->createPaymentIntent($user, [
                    'amount' => $plan->price,
                    'currency' => $plan->currency ?? 'USD',
                    'subscription_id' => $subscription->id,
                    'metadata' => [
                        'plan_name' => $plan->name,
                        'subscription_id' => $subscription->id,
                        'user_id' => $user->id,
                    ],
                ]);
                
                $subscription->update([
                    'payment_intent_id' => $payment['payment_intent_id'],
                ]);
            }

            // Activate subscription if specified
            if ($data['activate'] ?? false) {
                $this->activateSubscription($subscription);
            }

            // Send email
            if ($data['send_email'] ?? true) {
                $this->emailService->sendSubscriptionActivationEmail($subscription);
            }

            // Clear cache
            Cache::forget('user_subscriptions_' . $user->id);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\SubscriptionCreated', [
                'subscription' => $subscription,
                'user' => $user,
                'plan' => $plan,
            ]);

            return $subscription->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to create subscription', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function cancelExistingSubscription(Subscription $subscription): void
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Cancel with payment gateway
        if ($subscription->payment_method === 'stripe' && $subscription->stripe_subscription_id) {
            $this->paymentService->cancelSubscription($subscription->stripe_subscription_id);
        }

        // Remove user's plan
        $subscription->user->update(['plan_id' => null]);
    }

    protected function activateSubscription(Subscription $subscription): void
    {
        $subscription->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        // Update user's plan
        $subscription->user->update([
            'plan_id' => $subscription->plan_id,
        ]);

        // Assign roles
        $this->assignUserRoles($subscription->user, $subscription->plan);

        // Create invoice
        $this->createInvoice($subscription);
    }

    protected function assignUserRoles(User $user, Plan $plan): void
    {
        if ($plan->roles && is_array($plan->roles)) {
            $user->syncRoles($plan->roles);
        } else {
            $role = $plan->is_free ? 'customer' : 'premium_customer';
            $user->syncRoles([$role]);
        }
    }

    protected function createInvoice(Subscription $subscription): void
    {
        $invoice = $subscription->invoices()->create([
            'user_id' => $subscription->user_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'amount' => $subscription->amount,
            'currency' => $subscription->currency,
            'status' => 'pending',
            'due_date' => now()->addDays(7),
            'items' => [
                [
                    'description' => "Subscription: {$subscription->plan->name}",
                    'amount' => $subscription->amount,
                    'quantity' => 1,
                    'period' => 'monthly',
                ],
            ],
            'tax' => 0,
            'discount' => 0,
            'total' => $subscription->amount,
            'settings' => [
                'subscription_id' => $subscription->id,
                'plan_id' => $subscription->plan_id,
            ],
        ]);

        // Send invoice
        $this->dispatchEvent('App\Events\InvoiceCreated', [
            'invoice' => $invoice,
            'subscription' => $subscription,
        ]);
    }

    protected function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = \App\Models\Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $number = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $number = '0001';
        }
        
        return $prefix . $year . $month . $number;
    }

    protected function calculateEndDate(Plan $plan, $startDate): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($startDate)->addMonths($plan->duration);
    }

    protected function calculateTrialEnd(Plan $plan): ?\Carbon\Carbon
    {
        return $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null;
    }
}