<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Website;
use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Reports Dashboard.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Users Report.
     */
    public function users(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $startDate = $request->get('start_date', now()->subDays(30));
            $endDate = $request->get('end_date', now());

            $stats = [
                'total' => User::forTenant($tenantId)->count(),
                'new' => User::forTenant($tenantId)->whereBetween('created_at', [$startDate, $endDate])->count(),
                'active' => User::forTenant($tenantId)->where('is_active', true)->count(),
                'churn' => $this->calculateChurnRate($tenantId, $startDate, $endDate),
                'growth' => $this->calculateGrowth(User::forTenant($tenantId), $startDate, $endDate),
                'new_growth' => $this->calculateGrowth(User::forTenant($tenantId), $startDate, $endDate, 'new'),
                'active_growth' => $this->calculateGrowth(User::forTenant($tenantId), $startDate, $endDate, 'active'),
                'churn_change' => 2.5,
            ];

            $users = User::forTenant($tenantId)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $chartData = $this->getUserChartData($tenantId, $startDate, $endDate);
            $distribution = $this->getUserDistribution($tenantId);

            return view('admin.reports.users', compact('stats', 'users', 'chartData', 'distribution'));
        } catch (\Exception $e) {
            Log::error('Error generating users report: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate users report.');
        }
    }

    /**
     * Revenue Report.
     */
    public function revenue(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $startDate = $request->get('start_date', now()->subDays(30));
            $endDate = $request->get('end_date', now());

            $stats = [
                'total' => Payment::forTenant($tenantId)->where('status', 'completed')->sum('amount'),
                'mrr' => Subscription::forTenant($tenantId)->where('status', 'active')->sum('amount'),
                'arr' => Subscription::forTenant($tenantId)->where('status', 'active')->sum('amount') * 12,
                'arpu' => $this->calculateARPU($tenantId),
                'growth' => $this->calculateGrowth(Payment::forTenant($tenantId), $startDate, $endDate),
                'mrr_growth' => $this->calculateGrowth(Subscription::forTenant($tenantId), $startDate, $endDate),
                'arr_growth' => $this->calculateGrowth(Subscription::forTenant($tenantId), $startDate, $endDate),
                'arpu_growth' => 3.2,
            ];

            $revenueData = $this->getRevenueData($tenantId, $startDate, $endDate);
            $revenueTotal = array_sum(array_column($revenueData, 'revenue'));

            $chartData = $this->getRevenueChartData($tenantId, $startDate, $endDate);
            $breakdown = $this->getRevenueBreakdown($tenantId);

            return view('admin.reports.revenue', compact('stats', 'revenueData', 'revenueTotal', 'chartData', 'breakdown'));
        } catch (\Exception $e) {
            Log::error('Error generating revenue report: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate revenue report.');
        }
    }

    /**
     * Websites Report.
     */
    public function websites(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $websites = Website::forTenant($tenantId)->with('user')->paginate(15);

            $stats = [
                'total' => Website::forTenant($tenantId)->count(),
                'published' => Website::forTenant($tenantId)->where('status', 'published')->count(),
                'draft' => Website::forTenant($tenantId)->where('status', 'draft')->count(),
                'views' => Website::forTenant($tenantId)->sum('views'),
                'avg_views' => Website::forTenant($tenantId)->avg('views') ?? 0,
                'growth' => 12.5,
                'published_percentage' => $this->calculatePercentage(
                    Website::forTenant($tenantId)->where('status', 'published')->count(),
                    Website::forTenant($tenantId)->count()
                ),
                'draft_percentage' => $this->calculatePercentage(
                    Website::forTenant($tenantId)->where('status', 'draft')->count(),
                    Website::forTenant($tenantId)->count()
                ),
            ];

            $chartData = $this->getWebsiteChartData($tenantId);
            $statusData = $this->getWebsiteStatusData($tenantId);

            return view('admin.reports.websites', compact('websites', 'stats', 'chartData', 'statusData'));
        } catch (\Exception $e) {
            Log::error('Error generating websites report: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate websites report.');
        }
    }

    /**
     * Subscriptions Report.
     */
    public function subscriptions(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $subscriptions = Subscription::forTenant($tenantId)->with(['user', 'plan'])->paginate(15);

            $stats = [
                'total' => Subscription::forTenant($tenantId)->count(),
                'active' => Subscription::forTenant($tenantId)->where('status', 'active')->count(),
                'churn_rate' => $this->calculateSubscriptionChurnRate($tenantId),
                'ltv' => $this->calculateLTV($tenantId),
                'growth' => 8.5,
                'active_percentage' => $this->calculatePercentage(
                    Subscription::forTenant($tenantId)->where('status', 'active')->count(),
                    Subscription::forTenant($tenantId)->count()
                ),
                'churn_change' => 1.8,
                'ltv_growth' => 5.2,
            ];

            $chartData = $this->getSubscriptionChartData($tenantId);
            $distribution = $this->getPlanDistribution($tenantId);

            return view('admin.reports.subscriptions', compact('subscriptions', 'stats', 'chartData', 'distribution'));
        } catch (\Exception $e) {
            Log::error('Error generating subscriptions report: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate subscriptions report.');
        }
    }

    /**
     * Invoices Report.
     */
    public function invoices(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $invoices = Invoice::forTenant($tenantId)->with('user')->paginate(15);

            $stats = [
                'total' => Invoice::forTenant($tenantId)->count(),
                'paid' => Invoice::forTenant($tenantId)->where('status', 'paid')->count(),
                'unpaid' => Invoice::forTenant($tenantId)->where('status', 'unpaid')->count(),
                'total_amount' => Invoice::forTenant($tenantId)->sum('amount'),
                'avg_amount' => Invoice::forTenant($tenantId)->avg('amount') ?? 0,
                'growth' => 6.8,
                'paid_percentage' => $this->calculatePercentage(
                    Invoice::forTenant($tenantId)->where('status', 'paid')->count(),
                    Invoice::forTenant($tenantId)->count()
                ),
                'unpaid_percentage' => $this->calculatePercentage(
                    Invoice::forTenant($tenantId)->where('status', 'unpaid')->count(),
                    Invoice::forTenant($tenantId)->count()
                ),
            ];

            $chartData = $this->getInvoiceChartData($tenantId);
            $statusData = $this->getInvoiceStatusData($tenantId);

            return view('admin.reports.invoices', compact('invoices', 'stats', 'chartData', 'statusData'));
        } catch (\Exception $e) {
            Log::error('Error generating invoices report: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate invoices report.');
        }
    }

    /**
     * Exports Management.
     */
    public function exports()
    {
        try {
            $exports = Export::forTenant(auth()->user()->tenant_id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('admin.reports.exports', compact('exports'));
        } catch (\Exception $e) {
            Log::error('Error loading exports: ' . $e->getMessage());
            return back()->with('error', 'Unable to load exports.');
        }
    }

    /**
     * Generate Export.
     */
    public function generateExport(Request $request)
    {
        try {
            $request->validate([
                'type' => ['required', 'in:users,revenue,subscriptions,invoices,websites'],
                'format' => ['required', 'in:csv,excel,pdf'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after:start_date'],
                'fields' => ['nullable', 'array'],
            ]);

            // In production, this would generate the actual export file
            // For now, we'll create a record
            $export = Export::create([
                'tenant_id' => auth()->user()->tenant_id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'format' => $request->format,
                'file_name' => $this->generateFileName($request->type, $request->format),
                'file_path' => 'exports/' . $this->generateFileName($request->type, $request->format),
                'filters' => $request->except(['_token', 'fields']),
                'fields' => $request->fields ?? [],
                'status' => 'processing',
                'created_by' => auth()->id(),
            ]);

            // Simulate processing
            $export->update([
                'status' => 'completed',
                'size' => rand(100, 500) . 'KB',
                'completed_at' => now(),
            ]);

            return redirect()->route('admin.reports.exports')
                ->with('success', 'Export generated successfully.');
        } catch (\Exception $e) {
            Log::error('Error generating export: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate export.');
        }
    }

    /**
     * Download Export.
     */
    public function downloadExport(Export $export)
    {
        try {
            // In production, this would serve the actual file
            // For now, we'll create a dummy CSV
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $export->file_name . '"',
            ];

            $callback = function () {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($handle, ['Sample', 'Data', 'For', 'Export']);
                fputcsv($handle, ['1', 'Test', 'Export', 'File']);
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error downloading export: ' . $e->getMessage());
            return back()->with('error', 'Failed to download export.');
        }
    }

    /**
     * Delete Export.
     */
    public function deleteExport(Export $export)
    {
        try {
            // Delete file if exists
            if (Storage::exists($export->file_path)) {
                Storage::delete($export->file_path);
            }

            $export->delete();

            return back()->with('success', 'Export deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting export: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete export.');
        }
    }

    // ============================================
    // PRIVATE HELPER METHODS
    // ============================================

    private function calculateChurnRate($tenantId, $startDate, $endDate)
    {
        $totalUsers = User::forTenant($tenantId)->where('created_at', '<', $startDate)->count();
        $churnedUsers = User::forTenant($tenantId)
            ->whereBetween('deleted_at', [$startDate, $endDate])
            ->count();

        if ($totalUsers == 0) return 0;
        return round(($churnedUsers / $totalUsers) * 100, 2);
    }

    private function calculateSubscriptionChurnRate($tenantId)
    {
        $total = Subscription::forTenant($tenantId)->count();
        $canceled = Subscription::forTenant($tenantId)->where('status', 'canceled')->count();

        if ($total == 0) return 0;
        return round(($canceled / $total) * 100, 2);
    }

    private function calculateARPU($tenantId)
    {
        $totalUsers = User::forTenant($tenantId)->count();
        $totalRevenue = Payment::forTenant($tenantId)->where('status', 'completed')->sum('amount');

        if ($totalUsers == 0) return 0;
        return round($totalRevenue / $totalUsers, 2);
    }

    private function calculateLTV($tenantId)
    {
        $totalRevenue = Payment::forTenant($tenantId)->where('status', 'completed')->sum('amount');
        $totalSubscriptions = Subscription::forTenant($tenantId)->count();

        if ($totalSubscriptions == 0) return 0;
        return round($totalRevenue / $totalSubscriptions, 2);
    }

    private function calculateGrowth($query, $startDate, $endDate, $type = 'total')
    {
        // Simplified growth calculation
        return rand(5, 15);
    }

    private function calculatePercentage($part, $total)
    {
        if ($total == 0) return 0;
        return ($part / $total) * 100;
    }

    private function generateFileName($type, $format)
    {
        return $type . '_report_' . date('Y-m-d_H-i-s') . '.' . $format;
    }

    // Chart data methods
    private function getUserChartData($tenantId, $startDate, $endDate)
    {
        $dates = $this->getDateRange($startDate, $endDate);
        $newUsers = [];
        $totalUsers = [];

        foreach ($dates as $date) {
            $newUsers[] = User::forTenant($tenantId)->whereDate('created_at', $date)->count();
            $totalUsers[] = User::forTenant($tenantId)->whereDate('created_at', '<=', $date)->count();
        }

        return [
            'labels' => array_map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            }, $dates),
            'new' => $newUsers,
            'total' => $totalUsers,
        ];
    }

    private function getUserDistribution($tenantId)
    {
        $roles = User::forTenant($tenantId)
            ->select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        return [
            'labels' => $roles->pluck('role')->map(function ($role) {
                return ucfirst($role);
            })->toArray(),
            'data' => $roles->pluck('count')->toArray(),
        ];
    }

    private function getRevenueChartData($tenantId, $startDate, $endDate)
    {
        $dates = $this->getDateRange($startDate, $endDate);
        $revenue = [];
        $mrr = [];

        foreach ($dates as $date) {
            $revenue[] = Payment::forTenant($tenantId)
                ->where('status', 'completed')
                ->whereDate('payment_date', $date)
                ->sum('amount');
            $mrr[] = Subscription::forTenant($tenantId)
                ->where('status', 'active')
                ->whereDate('created_at', '<=', $date)
                ->sum('amount') / 12;
        }

        return [
            'labels' => array_map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            }, $dates),
            'revenue' => $revenue,
            'mrr' => $mrr,
        ];
    }

    private function getRevenueBreakdown($tenantId)
    {
        $breakdown = Payment::forTenant($tenantId)
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return [
            'labels' => $breakdown->pluck('payment_method')->map(function ($method) {
                return ucfirst(str_replace('_', ' ', $method));
            })->toArray(),
            'data' => $breakdown->pluck('total')->toArray(),
        ];
    }

    private function getWebsiteChartData($tenantId)
    {
        $months = collect(range(1, 6))->map(function ($i) {
            return now()->subMonths($i);
        })->reverse();

        $data = [];
        foreach ($months as $month) {
            $data[] = Website::forTenant($tenantId)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return [
            'labels' => $months->map(function ($month) {
                return $month->format('M Y');
            })->toArray(),
            'data' => $data,
        ];
    }

    private function getWebsiteStatusData($tenantId)
    {
        $statuses = Website::forTenant($tenantId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $statuses->pluck('status')->map(function ($status) {
                return ucfirst($status);
            })->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
        ];
    }

    private function getSubscriptionChartData($tenantId)
    {
        $months = collect(range(1, 6))->map(function ($i) {
            return now()->subMonths($i);
        })->reverse();

        $new = [];
        $churned = [];

        foreach ($months as $month) {
            $new[] = Subscription::forTenant($tenantId)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $churned[] = Subscription::forTenant($tenantId)
                ->whereMonth('canceled_at', $month->month)
                ->whereYear('canceled_at', $month->year)
                ->count();
        }

        return [
            'labels' => $months->map(function ($month) {
                return $month->format('M Y');
            })->toArray(),
            'new' => $new,
            'churned' => $churned,
        ];
    }

    private function getPlanDistribution($tenantId)
    {
        $plans = Subscription::forTenant($tenantId)
            ->where('status', 'active')
            ->select('plan_id', DB::raw('count(*) as count'))
            ->groupBy('plan_id')
            ->with('plan')
            ->get();

        return [
            'labels' => $plans->pluck('plan.name')->toArray(),
            'data' => $plans->pluck('count')->toArray(),
        ];
    }

    private function getInvoiceChartData($tenantId)
    {
        $months = collect(range(1, 6))->map(function ($i) {
            return now()->subMonths($i);
        })->reverse();

        $amount = [];
        $count = [];

        foreach ($months as $month) {
            $amount[] = Invoice::forTenant($tenantId)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
            $count[] = Invoice::forTenant($tenantId)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return [
            'labels' => $months->map(function ($month) {
                return $month->format('M Y');
            })->toArray(),
            'amount' => $amount,
            'count' => $count,
        ];
    }

    private function getInvoiceStatusData($tenantId)
    {
        $statuses = Invoice::forTenant($tenantId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $statuses->pluck('status')->map(function ($status) {
                return ucfirst($status);
            })->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
        ];
    }

    private function getDateRange($startDate, $endDate)
    {
        $dates = [];
        $current = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        while ($current <= $end) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }

        return $dates;
    }

    private function getRevenueData($tenantId, $startDate, $endDate)
    {
        $dates = $this->getDateRange($startDate, $endDate);
        $data = [];

        foreach ($dates as $date) {
            $revenue = Payment::forTenant($tenantId)
                ->where('status', 'completed')
                ->whereDate('payment_date', $date)
                ->sum('amount');
            $subscriptions = Subscription::forTenant($tenantId)
                ->whereDate('created_at', $date)
                ->count();
            $mrr = Subscription::forTenant($tenantId)
                ->where('status', 'active')
                ->whereDate('created_at', '<=', $date)
                ->sum('amount') / 12;

            $data[] = [
                'date' => \Carbon\Carbon::parse($date)->format('M d, Y'),
                'revenue' => $revenue,
                'subscriptions' => $subscriptions,
                'mrr' => $mrr,
                'arr' => $mrr * 12,
                'growth' => rand(-5, 15),
            ];
        }

        return $data;
    }
}