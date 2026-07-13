<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;     
use Illuminate\Support\Facades\Cache;  
use Illuminate\Support\Facades\Queue;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display dashboard.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $chartPeriod = $request->get('period', 'month');

            // Get data from service
            $stats = $this->dashboardService->getStats($tenantId);
            $chartData = $this->dashboardService->getChartData($tenantId, $chartPeriod);
            $activities = $this->dashboardService->getRecentActivities($tenantId);
            $recentSubscriptions = $this->dashboardService->getRecentSubscriptions($tenantId);

            // Get notification variables
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.dashboard.index', compact(
                'stats',
                'activities',
                'recentSubscriptions',
                'chartData',
                'chartPeriod',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading dashboard: ' . $e->getMessage());
            return back()->with('error', 'Unable to load dashboard.');
        }
    }

    /**
     * Get dashboard data via API.
     */
    public function getDashboardData(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $period = $request->get('period', 'month');

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $this->dashboardService->getStats($tenantId),
                    'charts' => $this->dashboardService->getChartData($tenantId, $period),
                    'activities' => $this->dashboardService->getRecentActivities($tenantId),
                    'subscriptions' => $this->dashboardService->getRecentSubscriptions($tenantId),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data.',
            ], 500);
        }
    }

    /**
     * Get dashboard stats (API).
     */
    public function getStats(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $stats = $this->dashboardService->getStats($tenantId);

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stats.',
            ], 500);
        }
    }

    /**
     * Get chart data (API).
     */
    public function getChartData(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $period = $request->get('period', 'month');
            $chartData = $this->dashboardService->getChartData($tenantId, $period);

            return response()->json([
                'success' => true,
                'data' => $chartData,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching chart data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chart data.',
            ], 500);
        }
    }

    /**
     * Get recent activities (API).
     */
    public function getRecentActivities(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $limit = $request->get('limit', 10);
            $activities = $this->dashboardService->getRecentActivities($tenantId, $limit);

            return response()->json([
                'success' => true,
                'data' => $activities,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching recent activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent activities.',
            ], 500);
        }
    }

    /**
     * Get recent subscriptions (API).
     */
    public function getRecentSubscriptions(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $limit = $request->get('limit', 5);
            $subscriptions = $this->dashboardService->getRecentSubscriptions($tenantId, $limit);

            return response()->json([
                'success' => true,
                'data' => $subscriptions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching recent subscriptions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent subscriptions.',
            ], 500);
        }
    }

    /**
     * Clear dashboard cache.
     */
    public function clearCache(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $this->dashboardService->clearCache($tenantId);

            return response()->json([
                'success' => true,
                'message' => 'Dashboard cache cleared successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing dashboard cache: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear dashboard cache.',
            ], 500);
        }
    }
   public function health()
{
    try {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'session' => $this->checkSession(),
            'storage' => $this->checkStorage(),
        ];

        $overallStatus = collect($checks)->every(fn($check) => $check['status'] === 'ok') 
            ? 'healthy' 
            : 'unhealthy';

        return response()->json([
            'status' => $overallStatus,
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ]);

    } catch (\Exception $e) {
        Log::error('Health check failed: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Health check failed: ' . $e->getMessage()
        ], 500);
    }
}

private function checkDatabase()
{
    try {
        DB::connection()->getPdo();
        $result = ['status' => 'ok', 'message' => 'Database connection successful'];
    } catch (\Exception $e) {
        $result = ['status' => 'error', 'message' => $e->getMessage()];
    }
    return $result;
}

private function checkCache()
{
    try {
        $testKey = 'health_test_' . time();
        Cache::put($testKey, 'ok', 1);
        $value = Cache::get($testKey);
        Cache::forget($testKey);
        
        $result = $value === 'ok' 
            ? ['status' => 'ok', 'message' => 'Cache working'] 
            : ['status' => 'error', 'message' => 'Cache read/write failed'];
    } catch (\Exception $e) {
        $result = ['status' => 'error', 'message' => $e->getMessage()];
    }
    return $result;
}

private function checkQueue()
{
    try {
        $queueSize = Queue::size('default');
        $result = [
            'status' => 'ok', 
            'message' => 'Queue is running',
            'pending_jobs' => $queueSize
        ];
    } catch (\Exception $e) {
        $result = ['status' => 'error', 'message' => $e->getMessage()];
    }
    return $result;
}

private function checkSession()
{
    try {
        $testKey = 'session_test';
        session()->put($testKey, 'ok');
        $value = session()->get($testKey);
        session()->forget($testKey);
        
        $result = $value === 'ok' 
            ? ['status' => 'ok', 'message' => 'Session working'] 
            : ['status' => 'error', 'message' => 'Session read/write failed'];
    } catch (\Exception $e) {
        $result = ['status' => 'error', 'message' => $e->getMessage()];
    }
    return $result;
}

private function checkStorage()
{
    try {
        $testFile = storage_path('app/health_test.txt');
        file_put_contents($testFile, 'ok');
        $content = file_get_contents($testFile);
        unlink($testFile);
        
        $result = $content === 'ok' 
            ? ['status' => 'ok', 'message' => 'Storage writable'] 
            : ['status' => 'error', 'message' => 'Storage read/write failed'];
    } catch (\Exception $e) {
        $result = ['status' => 'error', 'message' => $e->getMessage()];
    }
    return $result;
}

}