<?php

namespace App\Actions\Subscription;

use App\Actions\BaseAction;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PaymentService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Cache;

class AssignPlan extends BaseAction
{
    protected array $validationRules = [
        'user_id' => 'required|exists:users,id',
        'plan_id' => 'required|exists:plans,id',
        'auto_renew' => 'nullable|boolean',
        'trial_days' => 'nullable|integer|min:0',
        'activate_immediately' => 'nullable|boolean',
        'process_payment' => 'nullable|boolean',
        'payment_method' => 'nullable|string',
        'start_date' => 'nullable|date',
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

            // Cancel existing subscription if any
            $currentSubscription = $user->subscriptions()
                ->where('status', 'active')
                ->first();
            
            if ($currentSubscription) {
                $this->cancelCurrentSubscription($currentSubscription);
            }

            // Create new subscription
            $subscriptionData = [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'pending',
                'start_date' => $data['start_date'] ?? now(),
                'end_date' => $this->calculateEndDate($plan, $data['start_date'] ?? now()),
                'trial_ends_at' => $this->calculateTrialEnd($plan, $data['trial_days'] ?? null),
                'auto_renew' => $data['auto_renew'] ?? true,
                'payment_method' => $data['payment_method'] ?? null,
                'amount' => $plan->price,
                'currency' => $plan->currency ?? 'USD',
                'settings' => [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now()->toDateTimeString(),
                ],
            ];

            $subscription = Subscription::create($subscriptionData);

            // Process payment if required
            if ($data['process_payment'] ?? true) {
                $payment = $this->processPayment($user, $plan, $subscription);
                $subscription->update(['payment_intent_id' => $payment['payment_intent_id']]);
            }

            // Activate subscription if no payment required or trial
            if ($plan->trial_days > 0 || ($data['activate_immediately'] ?? false)) {
                $this->activateSubscription($subscription);
            }

            // Send confirmation email
            $this->emailService->sendSubscriptionActivationEmail($subscription);

            // Clear cache
            Cache::forget('user_subscriptions_' . $user->id);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\PlanAssigned', [
                'user' => $user,
                'plan' => $plan,
                'subscription' => $subscription,
            ]);

            return $subscription->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to assign plan', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function cancelCurrentSubscription(Subscription $subscription): void
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Cancel with payment gateway
        if ($subscription->payment_method === 'stripe') {
            $this->paymentService->cancelSubscription($subscription->stripe_subscription_id ?? '');
        }

        // Remove user's plan
        $subscription->user->update(['plan_id' => null]);
    }

    protected function processPayment(User $user, Plan $plan, Subscription $subscription): array
    {
        return $this->paymentService->createPaymentIntent($user, [
            'amount' => $plan->price,
            'currency' => $plan->currency ?? 'USD',
            'subscription_id' => $subscription->id,
            'metadata' => [
                'plan_name' => $plan->name,
                'subscription_id' => $subscription->id,
            ],
        ]);
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

    protected function calculateEndDate(Plan $plan, $startDate): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($startDate)->addMonths($plan->duration);
    }

    protected function calculateTrialEnd(Plan $plan, ?int $trialDays): ?\Carbon\Carbon
    {
        $days = $trialDays ?? $plan->trial_days;
        return $days > 0 ? now()->addDays($days) : null;
    }
}