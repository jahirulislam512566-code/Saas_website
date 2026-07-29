<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Apply filters
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        if ($request->user && $request->user !== 'all') {
            $query->where('user_id', $request->user);
        }
        
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->date_from) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->paginate(25);
        $users = User::select('id', 'name')->get();
        
        // Calculate stats
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('timestamp', today())->count(),
            'users' => ActivityLog::distinct('user_id')->count(),
            'errors' => round((ActivityLog::where('status', 'failed')->count() / max(ActivityLog::count(), 1)) * 100, 1)
        ];
        
        return view('admin.activity-logs.index', compact('logs', 'users', 'stats'));
    }
    
    public function login(Request $request)
    {
        $query = ActivityLog::with('user')->where('type', 'login');
        
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->user && $request->user !== 'all') {
            $query->where('user_id', $request->user);
        }
        
        if ($request->date_from) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->paginate(25);
        $users = User::select('id', 'name')->get();
        
        $stats = [
            'total' => ActivityLog::where('type', 'login')->count(),
            'successful' => ActivityLog::where('type', 'login')->where('status', 'success')->count(),
            'failed' => ActivityLog::where('type', 'login')->where('status', 'failed')->count(),
            'unique' => ActivityLog::where('type', 'login')->distinct('user_id')->count()
        ];
        
        return view('admin.activity-logs.login', compact('logs', 'users', 'stats'));
    }
    
    public function security(Request $request)
    {
        $query = ActivityLog::with('user')->where('category', 'security');
        
        if ($request->severity && $request->severity !== 'all') {
            $query->where('severity', $request->severity);
        }
        
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->paginate(25);
        
        $stats = [
            'total' => ActivityLog::where('category', 'security')->count(),
            'blocked' => ActivityLog::where('category', 'security')->where('status', 'blocked')->count(),
            'suspicious' => ActivityLog::where('category', 'security')->where('status', 'investigating')->count(),
            'score' => $this->calculateSecurityScore()
        ];
        
        return view('admin.activity-logs.security', compact('logs', 'stats'));
    }
    
    public function system(Request $request)
    {
        $query = ActivityLog::where('category', 'system');
        
        if ($request->level && $request->level !== 'all') {
            $query->where('level', $request->level);
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->paginate(25);
        
        $stats = [
            'error_rate' => round((ActivityLog::where('level', 'error')->count() / max(ActivityLog::count(), 1)) * 100, 2),
            'response_time' => rand(120, 250), // Simulated
            'api_calls' => ActivityLog::where('type', 'api')->whereDate('timestamp', today())->count()
        ];
        
        return view('admin.activity-logs.system', compact('logs', 'stats'));
    }
    
    public function fetch(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();
            
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('timestamp', today())->count(),
            'users' => ActivityLog::distinct('user_id')->count(),
            'errors' => round((ActivityLog::where('status', 'failed')->count() / max(ActivityLog::count(), 1)) * 100, 1)
        ];
        
        return response()->json([
            'logs' => $logs,
            'stats' => $stats
        ]);
    }
    
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return response()->json($log);
    }
    
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');
        
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->get();
        
        return response()->streamDownload(function() use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Action', 'Type', 'Status', 'IP', 'Timestamp']);
            
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->user->name ?? 'System',
                    $log->action,
                    $log->type,
                    $log->status,
                    $log->ip_address,
                    $log->timestamp
                ]);
            }
            
            fclose($handle);
        }, 'activity-logs-' . date('Y-m-d') . '.csv');
    }
    
    private function calculateSecurityScore()
    {
        $total = ActivityLog::where('category', 'security')->count();
        $blocked = ActivityLog::where('category', 'security')->where('status', 'blocked')->count();
        
        if ($total === 0) {
            return 100;
        }
        
        return max(0, 100 - (($blocked / $total) * 100));
    }
}