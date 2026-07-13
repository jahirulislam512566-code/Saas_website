<?php
// app/Services/DashboardService.php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Activity;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * Get dashboard statistics.
     *
     * @param int $tenantId
     * @return array
     */
    public function getStats($tenantId)
    {
        try {
            $cacheKey = 'dashboard_stats_' . $tenantId;
            
            return Cache::remember($cacheKey, 300, function () use ($tenantId) {
                $totalRevenue = Payment::forTenant($tenantId)
                    ->where('status', 'completed')
                    ->sum('amount');
                
                $previousRevenue = Payment::forTenant($tenantId)
                    ->where('status', 'completed')
                    ->where('created_at', '<', now()->subMonth())
                    ->sum('amount');

                $totalUsers = User::forTenant($tenantId)->count();
                $previousUsers = User::forTenant($tenantId)
                    ->where('created_at', '<', now()->subMonth())
                    ->count();

                $activeSubscriptions = Subscription::forTenant($tenantId)
                    ->where('status', 'active')
                    ->count();
                
                $previousSubscriptions = Subscription::forTenant($tenantId)
                    ->where('status', 'active')
                    ->where('created_at', '<', now()->subMonth())
                    ->count();

                $churnRate = $this->calculateChurnRate($tenantId);

                return [
                    'total_revenue' => $totalRevenue,
                    'revenue_growth' => $previousRevenue > 0 
                        ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 1) 
                        : 0,
                    'total_users' => $totalUsers,
                    'user_growth' => $previousUsers > 0 
                        ? round((($totalUsers - $previousUsers) / $previousUsers) * 100, 1) 
                        : 0,
                    'active_subscriptions' => $activeSubscriptions,
                    'subscription_growth' => $previousSubscriptions > 0 
                        ? round((($activeSubscriptions - $previousSubscriptions) / $previousSubscriptions) * 100, 1) 
                        : 0,
                    'churn_rate' => $churnRate,
                    'churn_change' => 0,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting stats: ' . $e->getMessage());
            return [
                'total_revenue' => 0,
                'revenue_growth' => 0,
                'total_users' => 0,
                'user_growth' => 0,
                'active_subscriptions' => 0,
                'subscription_growth' => 0,
                'churn_rate' => 0,
                'churn_change' => 0,
            ];
        }
    }

    /**
     * Get chart data.
     *
     * @param int $tenantId
     * @param string $period
     * @return array
     */
    public function getChartData($tenantId, $period = 'month')
    {
        try {
            $dates = $this->getDateRange($period);
            
            $revenueData = [];
            $userData = [];
            
            foreach ($dates as $date) {
                $revenueData[] = Payment::forTenant($tenantId)
                    ->where('status', 'completed')
                    ->whereDate('payment_date', $date)
                    ->sum('amount');
                    
                $userData[] = User::forTenant($tenantId)
                    ->whereDate('created_at', '<=', $date)
                    ->count();
            }

            // Plan distribution
            $planDistribution = Subscription::forTenant($tenantId)
                ->where('status', 'active')
                ->select('plan_id', DB::raw('count(*) as count'))
                ->groupBy('plan_id')
                ->with('plan')
                ->get();

            return [
                'revenue' => [
                    'labels' => array_map(function ($date) {
                        return \Carbon\Carbon::parse($date)->format('M d');
                    }, $dates),
                    'data' => $revenueData,
                ],
                'users' => [
                    'labels' => array_map(function ($date) {
                        return \Carbon\Carbon::parse($date)->format('M d');
                    }, $dates),
                    'data' => $userData,
                ],
                'plan_distribution' => [
                    'labels' => $planDistribution->pluck('plan.name')->toArray(),
                    'data' => $planDistribution->pluck('count')->toArray(),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Error getting chart data: ' . $e->getMessage());
            return [
                'revenue' => ['labels' => [], 'data' => []],
                'users' => ['labels' => [], 'data' => []],
                'plan_distribution' => ['labels' => [], 'data' => []],
            ];
        }
    }

    /**
     * Get recent activities.
     *
     * @param int $tenantId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentActivities($tenantId, $limit = 10)
    {
        try {
            return Activity::forTenant($tenantId)
                ->with(['user'])
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'user_name' => $activity->user->name ?? 'System',
                        'description' => $activity->description,
                        'action' => $activity->action,
                        'icon' => $this->getIconForAction($activity->action),
                        'color' => $this->getColorForAction($activity->action),
                        'time_ago' => $activity->created_at->diffForHumans(),
                        'created_at' => $activity->created_at->toISOString(),
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error getting recent activities: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get recent subscriptions.
     *
     * @param int $tenantId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentSubscriptions($tenantId, $limit = 5)
    {
        try {
            return Subscription::forTenant($tenantId)
                ->with(['user', 'plan'])
                ->latest()
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting recent subscriptions: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get revenue data for charts.
     *
     * @param int $tenantId
     * @param string $period
     * @return array
     */
    public function getRevenueData($tenantId, $period = 'month')
    {
        try {
            $dates = $this->getDateRange($period);
            $data = [];

            foreach ($dates as $date) {
                $dailyRevenue = Payment::forTenant($tenantId)
                    ->where('status', 'completed')
                    ->whereDate('payment_date', $date)
                    ->sum('amount');

                $data[] = [
                    'date' => \Carbon\Carbon::parse($date)->format('M d, Y'),
                    'revenue' => $dailyRevenue,
                    'subscriptions' => Subscription::forTenant($tenantId)
                        ->whereDate('created_at', $date)
                        ->count(),
                    'users' => User::forTenant($tenantId)
                        ->whereDate('created_at', $date)
                        ->count(),
                ];
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Error getting revenue data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get subscription metrics.
     *
     * @param int $tenantId
     * @return array
     */
    public function getSubscriptionMetrics($tenantId)
    {
        try {
            return [
                'total' => Subscription::forTenant($tenantId)->count(),
                'active' => Subscription::forTenant($tenantId)->where('status', 'active')->count(),
                'trialing' => Subscription::forTenant($tenantId)->where('status', 'trialing')->count(),
                'canceled' => Subscription::forTenant($tenantId)->where('status', 'canceled')->count(),
                'past_due' => Subscription::forTenant($tenantId)->where('status', 'past_due')->count(),
                'unpaid' => Subscription::forTenant($tenantId)->where('status', 'unpaid')->count(),
                'incomplete' => Subscription::forTenant($tenantId)->where('status', 'incomplete')->count(),
                'churn_rate' => $this->calculateChurnRate($tenantId),
                'mrr' => Subscription::forTenant($tenantId)
                    ->where('status', 'active')
                    ->sum('amount'),
                'arr' => Subscription::forTenant($tenantId)
                    ->where('status', 'active')
                    ->where('billing_cycle', 'yearly')
                    ->sum('amount'),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting subscription metrics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user metrics.
     *
     * @param int $tenantId
     * @return array
     */
    public function getUserMetrics($tenantId)
    {
        try {
            return [
                'total' => User::forTenant($tenantId)->count(),
                'active' => User::forTenant($tenantId)->where('is_active', true)->count(),
                'inactive' => User::forTenant($tenantId)->where('is_active', false)->count(),
                'new_today' => User::forTenant($tenantId)
                    ->whereDate('created_at', today())
                    ->count(),
                'new_this_week' => User::forTenant($tenantId)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'new_this_month' => User::forTenant($tenantId)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'with_subscriptions' => User::forTenant($tenantId)
                    ->whereHas('subscriptions', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->count(),
                'avg_revenue_per_user' => $this->calculateARPU($tenantId),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting user metrics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get date range for charts.
     *
     * @param string $period
     * @return array
     */
    private function getDateRange($period)
    {
        $dates = [];
        $start = now();
        
        switch ($period) {
            case 'today':
                $start = now()->startOfDay();
                break;
            case 'week':
                $start = now()->subDays(7);
                break;
            case 'month':
                $start = now()->subDays(30);
                break;
            case 'quarter':
                $start = now()->subDays(90);
                break;
            case 'year':
                $start = now()->subDays(365);
                break;
            default:
                $start = now()->subDays(30);
        }
        
        while ($start <= now()) {
            $dates[] = $start->toDateString();
            $start->addDay();
        }
        
        return $dates;
    }

    /**
     * Calculate churn rate.
     *
     * @param int $tenantId
     * @return float
     */
    private function calculateChurnRate($tenantId)
    {
        try {
            $active = Subscription::forTenant($tenantId)
                ->where('status', 'active')
                ->count();
            
            $canceled = Subscription::forTenant($tenantId)
                ->where('status', 'canceled')
                ->where('canceled_at', '>=', now()->subMonth())
                ->count();
            
            if ($active == 0 && $canceled == 0) {
                return 0;
            }
            
            $total = $active + $canceled;
            return round(($canceled / $total) * 100, 1);
        } catch (\Exception $e) {
            Log::error('Error calculating churn rate: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate ARPU (Average Revenue Per User).
     *
     * @param int $tenantId
     * @return float
     */
    private function calculateARPU($tenantId)
    {
        try {
            $totalRevenue = Payment::forTenant($tenantId)
                ->where('status', 'completed')
                ->sum('amount');
            
            $totalUsers = User::forTenant($tenantId)->count();
            
            if ($totalUsers == 0) {
                return 0;
            }
            
            return round($totalRevenue / $totalUsers, 2);
        } catch (\Exception $e) {
            Log::error('Error calculating ARPU: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get icon for action.
     *
     * @param string $action
     * @return string
     */
    private function getIconForAction($action)
    {
        $icons = [
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash',
            'viewed' => 'fa-eye',
            'logged_in' => 'fa-sign-in-alt',
            'logged_out' => 'fa-sign-out-alt',
            'published' => 'fa-rocket',
            'uploaded' => 'fa-upload',
            'downloaded' => 'fa-download',
            'created_user' => 'fa-user-plus',
            'updated_user' => 'fa-user-edit',
            'deleted_user' => 'fa-user-minus',
            'created_post' => 'fa-file-alt',
            'updated_post' => 'fa-edit',
            'deleted_post' => 'fa-trash-alt',
            'created_plan' => 'fa-crown',
            'updated_plan' => 'fa-edit',
            'deleted_plan' => 'fa-trash',
            'created_subscription' => 'fa-receipt',
            'updated_subscription' => 'fa-edit',
            'canceled_subscription' => 'fa-ban',
            'payment_received' => 'fa-credit-card',
            'payment_refunded' => 'fa-undo',
            'payment_failed' => 'fa-exclamation-circle',
            'created_website' => 'fa-globe',
            'updated_website' => 'fa-globe-edit',
            'deleted_website' => 'fa-globe-minus',
            'created_domain' => 'fa-flag',
            'updated_domain' => 'fa-flag-edit',
            'deleted_domain' => 'fa-flag-minus',
        ];
        return $icons[$action] ?? 'fa-circle';
    }

    /**
     * Get color for action.
     *
     * @param string $action
     * @return string
     */
    private function getColorForAction($action)
    {
        $colors = [
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'viewed' => 'gray',
            'logged_in' => 'indigo',
            'logged_out' => 'yellow',
            'published' => 'purple',
            'uploaded' => 'teal',
            'downloaded' => 'cyan',
            'created_user' => 'green',
            'updated_user' => 'blue',
            'deleted_user' => 'red',
            'created_post' => 'green',
            'updated_post' => 'blue',
            'deleted_post' => 'red',
            'created_plan' => 'green',
            'updated_plan' => 'blue',
            'deleted_plan' => 'red',
            'created_subscription' => 'green',
            'updated_subscription' => 'blue',
            'canceled_subscription' => 'red',
            'payment_received' => 'green',
            'payment_refunded' => 'yellow',
            'payment_failed' => 'red',
            'created_website' => 'green',
            'updated_website' => 'blue',
            'deleted_website' => 'red',
            'created_domain' => 'green',
            'updated_domain' => 'blue',
            'deleted_domain' => 'red',
        ];
        return $colors[$action] ?? 'gray';
    }

    /**
     * Clear dashboard cache.
     *
     * @param int $tenantId
     * @return void
     */
    public function clearCache($tenantId)
    {
        try {
            Cache::forget('dashboard_stats_' . $tenantId);
            Cache::forget('dashboard_chart_' . $tenantId);
            Cache::forget('dashboard_activities_' . $tenantId);
            Cache::forget('dashboard_subscriptions_' . $tenantId);
        } catch (\Exception $e) {
            Log::error('Error clearing dashboard cache: ' . $e->getMessage());
        }
    }

    /**
     * Get dashboard summary data.
     *
     * @param int $tenantId
     * @return array
     */
    public function getSummary($tenantId)
    {
        try {
            return [
                'stats' => $this->getStats($tenantId),
                'chart_data' => $this->getChartData($tenantId),
                'recent_activities' => $this->getRecentActivities($tenantId, 10),
                'recent_subscriptions' => $this->getRecentSubscriptions($tenantId, 5),
                'subscription_metrics' => $this->getSubscriptionMetrics($tenantId),
                'user_metrics' => $this->getUserMetrics($tenantId),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting dashboard summary: ' . $e->getMessage());
            return [];
        }
    }
}