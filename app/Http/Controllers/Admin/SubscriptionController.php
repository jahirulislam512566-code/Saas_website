<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
   public function index(Request $request)
    {
        try {
            $query = Subscription::with(['user', 'plan']);

            // Filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('plan_id')) {
                $query->where('plan_id', $request->plan_id);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            // Search
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($q2) => 
                        $q2->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%")
                    )->orWhereHas('plan', fn($q2) => 
                        $q2->where('name', 'LIKE', "%{$search}%")
                    );
                });
            }

            // Sorting
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = in_array($request->get('direction'), ['asc', 'desc']) 
                ? $request->get('direction') 
                : 'desc';

            $allowedSorts = ['id', 'status', 'price', 'created_at', 'updated_at', 'current_period_end'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $subscriptions = $query->paginate(15)->withQueryString();

            // Stats - FIXED column name from 'amount' to 'price'
            $stats = [
                'total'       => Subscription::count(),
                'active'      => Subscription::where('status', 'active')->count(),
                'trialing'    => Subscription::where('status', 'trialing')->count(),
                'canceled'    => Subscription::where('status', 'canceled')->count(),
                'past_due'    => Subscription::where('status', 'past_due')->count(),
                'total_revenue' => Subscription::where('status', 'active')->sum('price'),
                'monthly_revenue' => Subscription::where('status', 'active')
                                        ->where('billing_cycle', 'monthly')
                                        ->sum('price'),
                'yearly_revenue' => Subscription::where('status', 'active')
                                        ->where('billing_cycle', 'yearly')
                                        ->sum('price'),
            ];

            $plans = Plan::where('is_active', true)->get(['id', 'name']);

            return view('admin.subscriptions.index', compact('subscriptions', 'stats', 'plans'));
        } catch (\Exception $e) {
            Log::error('Subscriptions index error: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch subscriptions. Please try again.');
        }
    }

    /**
     * Show the form for creating a new subscription.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $users = User::all();
            $plans = Plan::where('is_active', true)->get();
            
            return view('admin.subscriptions.create', compact('users', 'plans'));
        } catch (\Exception $e) {
            Log::error('Error loading create subscription form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create subscription form.');
        }
    }

    /**
     * Store a newly created subscription.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'exists:users,id'],
                'plan_id' => ['required', 'exists:plans,id'],
                'status' => ['required', 'in:active,trialing,past_due,canceled,unpaid,incomplete,paused'],
                'amount' => ['required', 'numeric', 'min:0'],
                'currency' => ['required', 'string', 'size:3'],
                'billing_cycle' => ['required', 'in:monthly,quarterly,yearly,one-time'],
                'trial_ends_at' => ['nullable', 'date'],
                'current_period_start' => ['nullable', 'date'],
                'current_period_end' => ['nullable', 'date'],
                'payment_method' => ['nullable', 'string'],
                'payment_provider_id' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $subscription = Subscription::create($request->all());

                DB::commit();

                return redirect()->route('admin.subscriptions.index')
                    ->with('success', 'Subscription created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to create subscription. Please try again.');
        }
    }

    /**
     * Display the specified subscription.
     *
     * @param Subscription $subscription
     * @return \Illuminate\View\View
     */
    public function show(Subscription $subscription)
    {
        try {
            $subscription->load(['user', 'plan']);
            
            // Get payment history
            $payments = $subscription->payments()->latest()->limit(10)->get();
            
            // Get subscription events/logs
            $events = $subscription->events()->latest()->limit(10)->get();

            return view('admin.subscriptions.show', compact('subscription', 'payments', 'events'));
        } catch (\Exception $e) {
            Log::error('Error showing subscription: ' . $e->getMessage());
            return back()->with('error', 'Unable to display subscription details.');
        }
    }

    /**
     * Show the form for editing the specified subscription.
     *
     * @param Subscription $subscription
     * @return \Illuminate\View\View
     */
    public function edit(Subscription $subscription)
    {
        try {
            $users = User::all();
            $plans = Plan::where('is_active', true)->get();
            
            return view('admin.subscriptions.edit', compact('subscription', 'users', 'plans'));
        } catch (\Exception $e) {
            Log::error('Error loading edit subscription form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit subscription form.');
        }
    }

    /**
     * Update the specified subscription.
     *
     * @param Request $request
     * @param Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Subscription $subscription)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'exists:users,id'],
                'plan_id' => ['required', 'exists:plans,id'],
                'status' => ['required', 'in:active,trialing,past_due,canceled,unpaid,incomplete,paused'],
                'amount' => ['required', 'numeric', 'min:0'],
                'currency' => ['required', 'string', 'size:3'],
                'billing_cycle' => ['required', 'in:monthly,quarterly,yearly,one-time'],
                'trial_ends_at' => ['nullable', 'date'],
                'current_period_start' => ['nullable', 'date'],
                'current_period_end' => ['nullable', 'date'],
                'payment_method' => ['nullable', 'string'],
                'payment_provider_id' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $subscription->update($request->all());

                DB::commit();

                return redirect()->route('admin.subscriptions.index')
                    ->with('success', 'Subscription updated successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to update subscription. Please try again.');
        }
    }

    /**
     * Remove the specified subscription.
     *
     * @param Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subscription $subscription)
    {
        try {
            DB::beginTransaction();

            try {
                $subscription->delete();
                DB::commit();

                return redirect()->route('admin.subscriptions.index')
                    ->with('success', 'Subscription deleted successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete subscription. Please try again.');
        }
    }

    // ============ CUSTOM METHODS ============

    /**
     * Cancel a subscription.
     */
    public function cancel(Request $request, Subscription $subscription)
    {
        try {
            if ($subscription->status === 'canceled') {
                return back()->with('error', 'Subscription is already canceled.');
            }

            $subscription->status = 'canceled';
            $subscription->canceled_at = now();
            $subscription->save();

            return back()->with('success', 'Subscription canceled successfully.');
        } catch (\Exception $e) {
            Log::error('Error canceling subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel subscription.');
        }
    }

    /**
     * Resume a canceled subscription.
     */
    public function resume(Request $request, Subscription $subscription)
    {
        try {
            if ($subscription->status !== 'canceled') {
                return back()->with('error', 'Only canceled subscriptions can be resumed.');
            }

            $subscription->status = 'active';
            $subscription->canceled_at = null;
            $subscription->save();

            return back()->with('success', 'Subscription resumed successfully.');
        } catch (\Exception $e) {
            Log::error('Error resuming subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to resume subscription.');
        }
    }

    /**
     * Pause an active subscription.
     */
    public function pause(Request $request, Subscription $subscription)
    {
        try {
            if ($subscription->status !== 'active') {
                return back()->with('error', 'Only active subscriptions can be paused.');
            }

            $subscription->status = 'paused';
            $subscription->save();

            return back()->with('success', 'Subscription paused successfully.');
        } catch (\Exception $e) {
            Log::error('Error pausing subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to pause subscription.');
        }
    }

    /**
     * Resume a paused subscription.
     */
    public function resumePaused(Request $request, Subscription $subscription)
    {
        try {
            if ($subscription->status !== 'paused') {
                return back()->with('error', 'Only paused subscriptions can be resumed.');
            }

            $subscription->status = 'active';
            $subscription->save();

            return back()->with('success', 'Subscription resumed successfully.');
        } catch (\Exception $e) {
            Log::error('Error resuming paused subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to resume subscription.');
        }
    }

    /**
     * Update subscription plan.
     */
    public function updatePlan(Request $request, Subscription $subscription)
    {
        try {
            $request->validate([
                'plan_id' => ['required', 'exists:plans,id'],
            ]);

            $plan = Plan::findOrFail($request->plan_id);
            
            $subscription->plan_id = $plan->id;
            $subscription->amount = $plan->price_monthly;
            $subscription->save();

            return back()->with('success', 'Subscription plan updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating subscription plan: ' . $e->getMessage());
            return back()->with('error', 'Failed to update subscription plan.');
        }
    }

    /**
     * Renew a subscription.
     */
    public function renew(Request $request, Subscription $subscription)
    {
        try {
            // Extend current period
            if ($subscription->current_period_end) {
                $newEnd = \Carbon\Carbon::parse($subscription->current_period_end)->addMonth();
                $subscription->current_period_end = $newEnd;
            } else {
                $subscription->current_period_end = now()->addMonth();
            }
            $subscription->current_period_start = now();
            $subscription->save();

            return back()->with('success', 'Subscription renewed successfully.');
        } catch (\Exception $e) {
            Log::error('Error renewing subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to renew subscription.');
        }
    }

    /**
     * Bulk cancel subscriptions.
     */
    public function bulkCancel(Request $request)
    {
        try {
            $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['exists:subscriptions,id'],
            ]);

            $count = Subscription::whereIn('id', $request->ids)
                ->where('status', '!=', 'canceled')
                ->update([
                    'status' => 'canceled',
                    'canceled_at' => now(),
                ]);

            return back()->with('success', "{$count} subscriptions canceled successfully.");
        } catch (\Exception $e) {
            Log::error('Error bulk canceling subscriptions: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel subscriptions.');
        }
    }

    /**
     * Bulk pause subscriptions.
     */
    public function bulkPause(Request $request)
    {
        try {
            $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['exists:subscriptions,id'],
            ]);

            $count = Subscription::whereIn('id', $request->ids)
                ->where('status', 'active')
                ->update(['status' => 'paused']);

            return back()->with('success', "{$count} subscriptions paused successfully.");
        } catch (\Exception $e) {
            Log::error('Error bulk pausing subscriptions: ' . $e->getMessage());
            return back()->with('error', 'Failed to pause subscriptions.');
        }
    }

    /**
     * Export subscriptions to CSV.
     */
    public function export(Request $request)
    {
        try {
            $subscriptions = Subscription::with(['user', 'plan'])->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="subscriptions_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($subscriptions) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'ID', 'User', 'Email', 'Plan', 'Status', 'Amount', 
                    'Currency', 'Billing Cycle', 'Created At', 'Canceled At'
                ]);

                foreach ($subscriptions as $subscription) {
                    fputcsv($handle, [
                        $subscription->id,
                        $subscription->user->name ?? 'N/A',
                        $subscription->user->email ?? 'N/A',
                        $subscription->plan->name ?? 'N/A',
                        $subscription->status,
                        $subscription->amount,
                        $subscription->currency ?? 'USD',
                        $subscription->billing_cycle ?? 'monthly',
                        $subscription->created_at->format('Y-m-d H:i:s'),
                        $subscription->canceled_at ? $subscription->canceled_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting subscriptions: ' . $e->getMessage());
            return back()->with('error', 'Failed to export subscriptions.');
        }
    }

    /**
     * Get subscription metrics.
     */
    public function metrics(Request $request)
    {
        try {
            $metrics = [
                'total_subscriptions' => Subscription::count(),
                'active_subscriptions' => Subscription::where('status', 'active')->count(),
                'trialing_subscriptions' => Subscription::where('status', 'trialing')->count(),
                'canceled_subscriptions' => Subscription::where('status', 'canceled')->count(),
                'churn_rate' => $this->calculateChurnRate(),
                'mrr' => Subscription::where('status', 'active')->sum('amount'),
                'average_revenue_per_user' => $this->calculateAverageRevenuePerUser(),
                'lifetime_value' => $this->calculateLifetimeValue(),
            ];

            return view('admin.subscriptions.metrics', compact('metrics'));
        } catch (\Exception $e) {
            Log::error('Error fetching subscription metrics: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch metrics.');
        }
    }

    /**
     * Get subscription analytics.
     */
    public function analytics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            
            $data = [
                'new_subscriptions' => $this->getNewSubscriptionsData($period),
                'subscription_status' => $this->getSubscriptionStatusData(),
                'plan_distribution' => $this->getPlanDistributionData(),
                'revenue_trend' => $this->getRevenueTrendData($period),
            ];

            return view('admin.subscriptions.analytics', compact('data', 'period'));
        } catch (\Exception $e) {
            Log::error('Error fetching subscription analytics: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch analytics.');
        }
    }

    /**
     * Get churn rate data.
     */
    public function churnRate(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $data = $this->calculateChurnRateData($period);

            return view('admin.subscriptions.churn', compact('data', 'period'));
        } catch (\Exception $e) {
            Log::error('Error fetching churn rate data: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch churn rate data.');
        }
    }

    // ============ API METHODS (for frontend) ============

    /**
     * API endpoint to fetch subscriptions for frontend.
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = Subscription::with(['user', 'plan']);

            // ... (rest of apiIndex method as provided earlier)

            return response()->json([
                'success' => true,
                'data' => $formattedSubscriptions,
                'total' => $subscriptions->count(),
                'message' => 'Subscriptions fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch subscriptions. Please try again.',
            ], 500);
        }
    }

    // ============ PRIVATE HELPER METHODS ============

    private function calculateChurnRate()
    {
        $totalActive = Subscription::where('status', 'active')->count();
        $totalCanceled = Subscription::where('status', 'canceled')->count();
        
        if ($totalActive == 0) {
            return 0;
        }
        
        return round(($totalCanceled / ($totalActive + $totalCanceled)) * 100, 2);
    }

    private function calculateAverageRevenuePerUser()
    {
        $totalUsers = User::count();
        $totalRevenue = Subscription::where('status', 'active')->sum('amount');
        
        if ($totalUsers == 0) {
            return 0;
        }
        
        return round($totalRevenue / $totalUsers, 2);
    }

    private function calculateLifetimeValue()
    {
        $totalRevenue = Subscription::where('status', 'active')->sum('amount');
        $totalSubscriptions = Subscription::count();
        
        if ($totalSubscriptions == 0) {
            return 0;
        }
        
        return round($totalRevenue / $totalSubscriptions, 2);
    }

    private function getNewSubscriptionsData($period)
    {
        $startDate = now()->subMonths($period === 'year' ? 12 : ($period === 'quarter' ? 3 : 1));
        
        return Subscription::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    private function getSubscriptionStatusData()
    {
        return Subscription::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
    }

    private function getPlanDistributionData()
    {
        return Subscription::where('status', 'active')
            ->select('plan_id', \DB::raw('count(*) as count'))
            ->groupBy('plan_id')
            ->with('plan')
            ->get();
    }

    private function getRevenueTrendData($period)
    {
        $startDate = now()->subMonths($period === 'year' ? 12 : ($period === 'quarter' ? 3 : 1));
        
        return Subscription::where('status', 'active')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, sum(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    private function calculateChurnRateData($period)
    {
        $months = $period === 'year' ? 12 : ($period === 'quarter' ? 3 : 1);
        $data = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $startActive = Subscription::where('status', 'active')
                ->where('created_at', '<', $monthStart)
                ->count();
            
            $churned = Subscription::where('status', 'canceled')
                ->whereBetween('canceled_at', [$monthStart, $monthEnd])
                ->count();
            
            $churnRate = $startActive > 0 ? round(($churned / $startActive) * 100, 2) : 0;
            
            $data[] = [
                'month' => $month->format('M Y'),
                'churn_rate' => $churnRate,
            ];
        }
        
        return $data;
    }
}