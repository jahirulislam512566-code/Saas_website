<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AI\AIUsage;
use App\Models\AI\AIPrompt;
use App\Models\AI\AISetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $stats = $this->getDashboardStats();
        $recentActivities = $this->getRecentActivities();
        $chartData = $this->getChartData();
        
        return view('admin.ai.dashboard', compact('stats', 'recentActivities', 'chartData'));
    }
    
    // Prompts
    public function prompts(Request $request)
    {
        $query = AIPrompt::with('category');
        
        if ($request->category && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }
        
        if ($request->tool && $request->tool !== 'all') {
            $query->where('tool', $request->tool);
        }
        
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('prompt_text', 'like', "%{$search}%");
            });
        }
        
        $prompts = $query->orderBy('usage_count', 'desc')->paginate(12);
        $categories = AIPromptCategory::all();
        $stats = $this->getPromptStats();
        
        return view('admin.ai.prompts', compact('prompts', 'categories', 'stats'));
    }
    
    // Usage
    public function usage(Request $request)
    {
        $period = $request->period ?? 'month';
        $tool = $request->tool ?? 'all';
        
        $query = AIUsage::with('user');
        
        if ($tool !== 'all') {
            $query->where('tool', $tool);
        }
        
        $dateRange = $this->getDateRange($period);
        $query->whereBetween('created_at', $dateRange);
        
        $usageLogs = $query->orderBy('created_at', 'desc')->paginate(20);
        $stats = $this->getUsageStats($period, $tool);
        $chartData = $this->getUsageChartData($period, $tool);
        
        return view('admin.ai.usage', compact('usageLogs', 'stats', 'chartData'));
    }
    
    // Settings
    public function settings()
    {
        $providers = $this->getProviders();
        $toolModels = $this->getToolModels();
        $limits = $this->getLimits();
        $tools = $this->getTools();
        $security = $this->getSecuritySettings();
        
        return view('admin.ai.settings', compact('providers', 'toolModels', 'limits', 'tools', 'security'));
    }
    
    private function getDashboardStats()
    {
        $totalRequests = AIUsage::count();
        $previousRequests = AIUsage::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = AIUsage::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
        
        return [
            'total_requests' => $totalRequests,
            'requests_growth' => $previousMonth > 0 ? round((($previousRequests - $previousMonth) / $previousMonth) * 100, 1) : 0,
            'tokens_used' => AIUsage::sum('tokens'),
            'tokens_remaining' => 1000000 - AIUsage::sum('tokens'),
            'active_users' => AIUsage::distinct('user_id')->where('created_at', '>=', now()->subDays(30))->count(),
            'total_users' => User::where('role', 'customer')->count(),
            'avg_response_time' => round(AIUsage::avg('duration'), 0),
            'response_improvement' => 12
        ];
    }
    
    private function getRecentActivities($limit = 10)
    {
        return AIUsage::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($log) {
                $icons = [
                    'writer' => 'fa-pen-fancy',
                    'seo' => 'fa-magnifying-glass-chart',
                    'image' => 'fa-image',
                    'website' => 'fa-wand-magic-sparkles',
                    'chat' => 'fa-comment-dots',
                    'translate' => 'fa-language'
                ];
                
                $names = [
                    'writer' => 'AI Writer',
                    'seo' => 'SEO Generator',
                    'image' => 'Image Generator',
                    'website' => 'Website Generator',
                    'chat' => 'Chat Assistant',
                    'translate' => 'Translation'
                ];
                
                return [
                    'id' => $log->id,
                    'user' => $log->user->name ?? 'Unknown',
                    'tool' => $log->tool,
                    'tool_name' => $names[$log->tool] ?? $log->tool,
                    'icon' => $icons[$log->tool] ?? 'fa-robot',
                    'description' => substr($log->prompt, 0, 60) . '...',
                    'timestamp' => $log->created_at,
                    'status' => $log->status
                ];
            });
    }
    
    private function getChartData()
    {
        // Usage chart data
        $labels = [];
        $requests = [];
        $tokens = [];
        
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            $requests[] = AIUsage::whereDate('created_at', $date)->count();
            $tokens[] = AIUsage::whereDate('created_at', $date)->sum('tokens');
        }
        
        // Distribution data
        $distribution = AIUsage::select('tool', DB::raw('count(*) as count'))
            ->groupBy('tool')
            ->get();
        
        $toolLabels = ['writer', 'seo', 'image', 'website', 'chat', 'translate'];
        $toolNames = [
            'writer' => 'AI Writer',
            'seo' => 'SEO Generator',
            'image' => 'Image Generator',
            'website' => 'Website Generator',
            'chat' => 'Chat Assistant',
            'translate' => 'Translation'
        ];
        
        $distLabels = [];
        $distValues = [];
        
        foreach ($toolLabels as $tool) {
            $count = $distribution->where('tool', $tool)->first()->count ?? 0;
            if ($count > 0) {
                $distLabels[] = $toolNames[$tool] ?? $tool;
                $distValues[] = $count;
            }
        }
        
        return [
            'usage' => [
                'labels' => $labels,
                'requests' => $requests,
                'tokens' => $tokens
            ],
            'distribution' => [
                'labels' => $distLabels,
                'values' => $distValues
            ]
        ];
    }
    
    private function getPromptStats()
    {
        return [
            'total' => AIPrompt::count(),
            'categories' => AIPromptCategory::count(),
            'most_used' => AIPrompt::max('usage_count') ?? 0,
            'success_rate' => round((AIUsage::where('status', 'success')->count() / max(AIUsage::count(), 1)) * 100, 1)
        ];
    }
    
    private function getUsageStats($period, $tool)
    {
        $dateRange = $this->getDateRange($period);
        $previousRange = [
            $dateRange[0]->copy()->subDays($dateRange[0]->diffInDays($dateRange[1])),
            $dateRange[0]->copy()->subDay()
        ];
        
        $query = AIUsage::whereBetween('created_at', $dateRange);
        if ($tool !== 'all') {
            $query->where('tool', $tool);
        }
        
        $previousQuery = AIUsage::whereBetween('created_at', $previousRange);
        if ($tool !== 'all') {
            $previousQuery->where('tool', $tool);
        }
        
        $totalRequests = $query->count();
        $previousRequests = $previousQuery->count();
        
        return [
            'total_requests' => $totalRequests,
            'requests_growth' => $previousRequests > 0 ? round((($totalRequests - $previousRequests) / $previousRequests) * 100, 1) : 0,
            'total_tokens' => $query->sum('tokens'),
            'avg_tokens_per_request' => $totalRequests > 0 ? round($query->sum('tokens') / $totalRequests, 0) : 0,
            'total_cost' => $query->sum('cost'),
            'cost_per_request' => $totalRequests > 0 ? $query->sum('cost') / $totalRequests : 0,
            'success_rate' => round(($query->where('status', 'success')->count() / max($totalRequests, 1)) * 100, 1),
            'failed_requests' => $query->where('status', 'failed')->count()
        ];
    }
    
    private function getUsageChartData($period, $tool)
    {
        $dateRange = $this->getDateRange($period);
        $days = $dateRange[0]->diffInDays($dateRange[1]);
        
        $labels = [];
        $requests = [];
        $tokens = [];
        
        $baseQuery = AIUsage::query();
        if ($tool !== 'all') {
            $baseQuery->where('tool', $tool);
        }
        
        for ($i = 0; $i <= $days; $i++) {
            $date = $dateRange[0]->copy()->addDays($i);
            $labels[] = $date->format('M d');
            
            $requests[] = (clone $baseQuery)->whereDate('created_at', $date)->count();
            $tokens[] = (clone $baseQuery)->whereDate('created_at', $date)->sum('tokens');
        }
        
        // Cost breakdown
        $costBreakdown = AIUsage::select('tool', DB::raw('sum(cost) as total_cost'))
            ->whereBetween('created_at', $dateRange)
            ->groupBy('tool')
            ->get();
        
        $toolNames = [
            'writer' => 'AI Writer',
            'seo' => 'SEO Generator',
            'image' => 'Image Generator',
            'website' => 'Website Generator',
            'chat' => 'Chat Assistant',
            'translate' => 'Translation'
        ];
        
        $costLabels = [];
        $costValues = [];
        
        foreach ($costBreakdown as $item) {
            $costLabels[] = $toolNames[$item->tool] ?? $item->tool;
            $costValues[] = $item->total_cost;
        }
        
        return [
            'usage_over_time' => [
                'labels' => $labels,
                'requests' => $requests,
                'tokens' => $tokens
            ],
            'cost_breakdown' => [
                'labels' => $costLabels,
                'values' => $costValues
            ]
        ];
    }
    
    private function getDateRange($period)
    {
        $end = now();
        
        switch ($period) {
            case 'today':
                $start = now()->startOfDay();
                break;
            case 'week':
                $start = now()->subWeek();
                break;
            case 'month':
                $start = now()->subMonth();
                break;
            case 'quarter':
                $start = now()->subQuarter();
                break;
            case 'year':
                $start = now()->subYear();
                break;
            default:
                $start = now()->subMonth();
        }
        
        return [$start, $end];
    }
}