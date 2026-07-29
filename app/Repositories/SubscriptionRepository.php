<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionRepository extends BaseRepository
{
    public function __construct(Subscription $subscription)
    {
        parent::__construct($subscription);
    }

    public function findByUser(User $user): Collection
    {
        return $this->model->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findActiveByUser(User $user): ?Subscription
    {
        return $this->model->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
    }

    public function findByPlan(Plan $plan): Collection
    {
        return $this->model->where('plan_id', $plan->id)
            ->where('status', 'active')
            ->get();
    }

    public function getActiveSubscriptions(): Collection
    {
        return $this->model->where('status', 'active')
            ->with(['user', 'plan'])
            ->get();
    }

    public function getExpiredSubscriptions(): Collection
    {
        return $this->model->where('status', 'active')
            ->where('end_date', '<', now())
            ->get();
    }

    public function getExpiringSubscriptions(int $days = 7): Collection
    {
        return $this->model->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays($days)])
            ->with(['user', 'plan'])
            ->get();
    }

    public function getTrialSubscriptions(): Collection
    {
        return $this->model->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now())
            ->where('status', 'active')
            ->with(['user', 'plan'])
            ->get();
    }

    public function getExpiredTrials(): Collection
    {
        return $this->model->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', now())
            ->where('status', 'active')
            ->with(['user', 'plan'])
            ->get();
    }

    public function createSubscription(User $user, Plan $plan, array $data = []): Subscription
    {
        $data['user_id'] = $user->id;
        $data['plan_id'] = $plan->id;
        $data['start_date'] = $data['start_date'] ?? now();
        $data['end_date'] = $this->calculateEndDate($plan, $data['start_date']);
        
        return $this->create($data);
    }

    public function updateSubscription(Subscription $subscription, array $data): Subscription
    {
        return $this->update($subscription, $data);
    }

    public function activateSubscription(Subscription $subscription): Subscription
    {
        return $this->update($subscription, [
            'status' => 'active',
            'activated_at' => now(),
        ]);
    }

    public function cancelSubscription(Subscription $subscription): Subscription
    {
        return $this->update($subscription, [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function pauseSubscription(Subscription $subscription): Subscription
    {
        return $this->update($subscription, [
            'status' => 'paused',
            'paused_at' => now(),
        ]);
    }

    public function resumeSubscription(Subscription $subscription): Subscription
    {
        return $this->update($subscription, [
            'status' => 'active',
            'resumed_at' => now(),
        ]);
    }

    public function renewSubscription(Subscription $subscription): Subscription
    {
        $plan = $subscription->plan;
        $newEndDate = $this->calculateEndDate($plan, now());
        
        return $this->update($subscription, [
            'end_date' => $newEndDate,
            'renewed_at' => now(),
            'status' => 'active',
        ]);
    }

    public function expireSubscription(Subscription $subscription): Subscription
    {
        return $this->update($subscription, [
            'status' => 'expired',
            'expired_at' => now(),
        ]);
    }

    public function changePlan(Subscription $subscription, Plan $newPlan): Subscription
    {
        return $this->update($subscription, [
            'plan_id' => $newPlan->id,
            'end_date' => $this->calculateEndDate($newPlan, now()),
        ]);
    }

    public function getSubscriptionStats(): array
    {
        return [
            'total' => $this->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'cancelled' => $this->model->where('status', 'cancelled')->count(),
            'paused' => $this->model->where('status', 'paused')->count(),
            'expired' => $this->model->where('status', 'expired')->count(),
            'trials' => $this->model->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now())->count(),
            'auto_renew' => $this->model->where('auto_renew', true)->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'annual_revenue' => $this->getAnnualRevenue(),
        ];
    }

    public function getSubscriptionByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)
            ->with(['user', 'plan'])
            ->get();
    }

    public function getSubscriptionUsage(User $user): array
    {
        $subscription = $this->findActiveByUser($user);
        
        if (!$subscription) {
            return [];
        }
        
        $plan = $subscription->plan;
        
        return [
            'websites_used' => $user->websites()->count(),
            'websites_limit' => $plan->max_websites,
            'storage_used' => $this->getStorageUsage($user),
            'storage_limit' => $plan->storage_limit,
            'bandwidth_used' => $this->getBandwidthUsage($user),
            'bandwidth_limit' => $plan->bandwidth_limit,
            'usage_percentage' => [
                'websites' => $plan->max_websites > 0 ? round(($user->websites()->count() / $plan->max_websites) * 100, 2) : 0,
                'storage' => $plan->storage_limit > 0 ? round(($this->getStorageUsage($user) / $plan->storage_limit) * 100, 2) : 0,
                'bandwidth' => $plan->bandwidth_limit > 0 ? round(($this->getBandwidthUsage($user) / $plan->bandwidth_limit) * 100, 2) : 0,
            ],
        ];
    }

    public function searchSubscriptions(string $searchTerm): Collection
    {
        return $this->model->whereHas('user', function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
        })->orWhereHas('plan', function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        })->with(['user', 'plan'])->get();
    }

    public function getSubscriptionRevenueByPlan(): array
    {
        return $this->model->where('status', 'active')
            ->groupBy('plan_id')
            ->selectRaw('plan_id, count(*) as count, sum(amount) as total')
            ->with('plan')
            ->get()
            ->pluck('total', 'plan.name')
            ->toArray();
    }

    public function getSubscriptionGrowth(): array
    {
        $growth = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $growth[$date->format('M Y')] = $this->model->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }
        
        return $growth;
    }

    public function getUpcomingRenewals(int $days = 7): Collection
    {
        return $this->model->where('status', 'active')
            ->where('auto_renew', true)
            ->whereBetween('end_date', [now()->addDays(1), now()->addDays($days)])
            ->with(['user', 'plan'])
            ->get();
    }

    public function getFailedRenewals(): Collection
    {
        return $this->model->where('status', 'active')
            ->where('auto_renew', true)
            ->where('end_date', '<', now())
            ->whereDoesntHave('payments', function ($query) {
                $query->where('status', 'succeeded')
                      ->where('created_at', '>=', now()->subDays(7));
            })
            ->with(['user', 'plan'])
            ->get();
    }

    public function getSubscriptionChurnRate(): float
    {
        $activeAtStart = $this->model->where('status', 'active')
            ->where('created_at', '<=', now()->subMonth())
            ->count();
        
        $cancelledDuringMonth = $this->model->where('status', 'cancelled')
            ->whereBetween('cancelled_at', [now()->subMonth(), now()])
            ->count();
        
        return $activeAtStart > 0 ? ($cancelledDuringMonth / $activeAtStart) * 100 : 0;
    }

    public function getCustomerLifetimeValue(): float
    {
        $activeSubscriptions = $this->model->where('status', 'active')->get();
        $totalRevenue = $this->getMonthlyRevenue();
        $totalSubscriptions = $activeSubscriptions->count();
        
        return $totalSubscriptions > 0 ? $totalRevenue / $totalSubscriptions : 0;
    }

    private function calculateEndDate(Plan $plan, $startDate): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($startDate)->addMonths($plan->duration);
    }

    private function getStorageUsage(User $user): int
    {
        return $user->media()->sum('size');
    }

    private function getBandwidthUsage(User $user): int
    {
        // Implement bandwidth tracking logic here
        return 0;
    }

    private function getMonthlyRevenue(): float
    {
        return $this->model->where('status', 'active')
            ->where('end_date', '>=', now())
            ->sum('amount');
    }

    private function getAnnualRevenue(): float
    {
        $startOfYear = now()->startOfYear();
        return $this->model->where('status', 'active')
            ->where('created_at', '>=', $startOfYear)
            ->sum('amount');
    }
}