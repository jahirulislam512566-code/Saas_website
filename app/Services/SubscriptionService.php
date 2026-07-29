<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionService
{
    public function createSubscription(User $user, Plan $plan, array $data = []): Subscription
    {
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
            'start_date' => $data['start_date'] ?? now(),
            'end_date' => $this->calculateEndDate($plan, $data['start_date'] ?? now()),
            'trial_ends_at' => $this->calculateTrialEnd($plan),
            'auto_renew' => $data['auto_renew'] ?? true,
            'payment_method' => $data['payment_method'] ?? null,
            'settings' => $data['settings'] ?? [],
        ]);

        return $subscription;
    }

    public function activateSubscription(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        // Update user role/permissions
        $this->updateUserRole($subscription->user, $subscription->plan);

        return $subscription;
    }

    public function cancelSubscription(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return $subscription;
    }

    public function pauseSubscription(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'paused',
            'paused_at' => now(),
        ]);

        return $subscription;
    }

    public function resumeSubscription(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'active',
            'resumed_at' => now(),
        ]);

        return $subscription;
    }

    public function renewSubscription(Subscription $subscription): Subscription
    {
        $plan = $subscription->plan;
        
        $subscription->update([
            'end_date' => $this->calculateEndDate($plan, now()),
            'renewed_at' => now(),
            'status' => 'active',
        ]);

        return $subscription;
    }

    public function checkExpiringSubscriptions(): void
    {
        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->get();

        foreach ($expiringSoon as $subscription) {
            // Send expiration notification
            $this->sendExpirationNotification($subscription);
        }
    }

    public function checkExpiredSubscriptions(): void
    {
        $expired = Subscription::where('status', 'active')
            ->where('end_date', '<', now())
            ->get();

        foreach ($expired as $subscription) {
            if ($subscription->auto_renew) {
                $this->autoRenew($subscription);
            } else {
                $this->expireSubscription($subscription);
            }
        }
    }

    private function autoRenew(Subscription $subscription): void
    {
        // Process payment and renew
        try {
            $paymentService = app(PaymentService::class);
            $payment = $paymentService->processSubscriptionPayment($subscription);
            
            if ($payment->status === 'succeeded') {
                $this->renewSubscription($subscription);
            } else {
                $this->expireSubscription($subscription);
            }
        } catch (\Exception $e) {
            $this->expireSubscription($subscription);
        }
    }

    private function expireSubscription(Subscription $subscription): void
    {
        $subscription->update([
            'status' => 'expired',
            'expired_at' => now(),
        ]);

        // Update user role to free plan
        $this->updateUserRole($subscription->user, null);
    }

    private function calculateEndDate(Plan $plan, $startDate): Carbon
    {
        return Carbon::parse($startDate)->addMonths($plan->duration);
    }

    private function calculateTrialEnd(Plan $plan): ?Carbon
    {
        if ($plan->trial_days > 0) {
            return now()->addDays($plan->trial_days);
        }
        return null;
    }

    private function updateUserRole(User $user, ?Plan $plan): void
    {
        if ($plan) {
            $user->syncRoles($plan->roles ?? ['customer']);
            $user->update(['plan_id' => $plan->id]);
        } else {
            $user->syncRoles(['customer']);
            $user->update(['plan_id' => null]);
        }
    }

    private function sendExpirationNotification(Subscription $subscription): void
    {
        // Send email notification
        $emailService = app(EmailService::class);
        $emailService->sendSubscriptionExpiringEmail($subscription);
    }

    public function getUsage(Subscription $subscription): array
    {
        return [
            'websites_used' => $subscription->user->websites()->count(),
            'websites_limit' => $subscription->plan->max_websites,
            'storage_used' => $this->getStorageUsage($subscription->user),
            'storage_limit' => $subscription->plan->storage_limit,
            'bandwidth_used' => $this->getBandwidthUsage($subscription->user),
            'bandwidth_limit' => $subscription->plan->bandwidth_limit,
        ];
    }

    private function getStorageUsage(User $user): int
    {
        // Implement storage usage calculation
        return 0;
    }

    private function getBandwidthUsage(User $user): int
    {
        // Implement bandwidth usage calculation
        return 0;
    }
}