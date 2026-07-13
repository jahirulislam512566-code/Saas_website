<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = Auth::user()->tenant_id;

            $query = ActivityLog::forTenant($tenantId)
                ->with(['user'])
                ->latest();

            // Filter by action
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            // Filter by user
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('action', 'like', "%{$search}%")
                      ->orWhere('subject_name', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $activities = $query->paginate(20);
            
            // Get distinct actions for filter
            $actions = ActivityLog::forTenant($tenantId)
                ->distinct()
                ->pluck('action')
                ->toArray();
            
            // Get users for filter
            $users = User::forTenant($tenantId)->orderBy('name')->get();

            // ===== STATS FOR VIEW =====
            $stats = [
                'total' => ActivityLog::forTenant($tenantId)->count(),
                'today' => ActivityLog::forTenant($tenantId)
                    ->whereDate('created_at', today())
                    ->count(),
                'this_week' => ActivityLog::forTenant($tenantId)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'this_month' => ActivityLog::forTenant($tenantId)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];

            // ===== NOTIFICATION VARIABLES FOR TOP NAV =====
            $user = Auth::user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.activities.index', compact(
                'activities', 
                'actions', 
                'users',
                'stats',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching activities: ' . $e->getMessage());
            return back()->with('error', 'Unable to load activities. Please try again.');
        }
    }

    /**
     * Display the specified activity.
     */
    public function show(ActivityLog $activity)
    {
        try {
            $this->authorizeTenant($activity);
            $activity->load(['user', 'subject']);

            return view('admin.activities.show', compact('activity'));
        } catch (\Exception $e) {
            Log::error('Error showing activity: ' . $e->getMessage());
            return back()->with('error', 'Unable to display activity details.');
        }
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(ActivityLog $activity)
    {
        try {
            $this->authorizeTenant($activity);
            $activity->delete();

            return redirect()->route('admin.activities.index')
                ->with('success', 'Activity deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting activity: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete activity.');
        }
    }

    /**
     * Remove the specified activity via POST.
     */
    public function delete(ActivityLog $activity)
    {
        return $this->destroy($activity);
    }

    /**
     * Clear all activities.
     */
    public function clear(Request $request)
    {
        try {
            $tenantId = Auth::user()->tenant_id;
            
            ActivityLog::forTenant($tenantId)->delete();

            return redirect()->route('admin.activities.index')
                ->with('success', 'All activities cleared successfully.');
        } catch (\Exception $e) {
            Log::error('Error clearing activities: ' . $e->getMessage());
            return back()->with('error', 'Failed to clear activities.');
        }
    }

    /**
     * Export activities to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = Auth::user()->tenant_id;

            $query = ActivityLog::forTenant($tenantId)
                ->with(['user']);

            // Apply filters if provided
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $activities = $query->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=activities_' . date('Y-m-d') . '.csv',
            ];

            $callback = function () use ($activities) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Add headers
                fputcsv($file, [
                    'ID',
                    'User',
                    'Action',
                    'Description',
                    'Subject',
                    'Subject Type',
                    'IP Address',
                    'User Agent',
                    'Date'
                ]);

                // Add data
                foreach ($activities as $activity) {
                    fputcsv($file, [
                        $activity->id,
                        $activity->user->name ?? 'System',
                        $activity->action,
                        $activity->description,
                        $activity->subject_name ?? 'N/A',
                        class_basename($activity->subject_type ?? ''),
                        $activity->ip_address ?? 'N/A',
                        $activity->user_agent ?? 'N/A',
                        $activity->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting activities: ' . $e->getMessage());
            return back()->with('error', 'Failed to export activities.');
        }
    }

    /**
     * Filter activities via AJAX.
     */
    public function filter(Request $request)
    {
        try {
            $tenantId = Auth::user()->tenant_id;

            $query = ActivityLog::forTenant($tenantId)
                ->with(['user'])
                ->latest();

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $activities = $query->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $activities,
            ]);
        } catch (\Exception $e) {
            Log::error('Error filtering activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to filter activities.',
            ], 500);
        }
    }

    /**
     * Get activities via API (for AJAX).
     */
    public function apiList(Request $request)
    {
        try {
            $tenantId = Auth::user()->tenant_id;

            $query = ActivityLog::forTenant($tenantId)
                ->with(['user'])
                ->latest();

            if ($request->filled('limit')) {
                $query->limit((int) $request->limit);
            } else {
                $query->limit(10);
            }

            $activities = $query->get()->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user' => $activity->user->name ?? 'System',
                    'action' => $activity->action,
                    'icon' => $activity->icon ?? $this->getIconForAction($activity->action),
                    'color' => $activity->color ?? $this->getColorForAction($activity->action),
                    'description' => $activity->description,
                    'time' => $activity->created_at->diffForHumans(),
                    'timestamp' => $activity->created_at->toISOString(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $activities,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching activities API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activities.',
            ], 500);
        }
    }

    /**
     * Get activity statistics.
     */
    public function stats(Request $request)
    {
        try {
            $tenantId = Auth::user()->tenant_id;

            $stats = [
                'total' => ActivityLog::forTenant($tenantId)->count(),
                'today' => ActivityLog::forTenant($tenantId)
                    ->whereDate('created_at', today())
                    ->count(),
                'this_week' => ActivityLog::forTenant($tenantId)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'this_month' => ActivityLog::forTenant($tenantId)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];

            // Get top actions
            $topActions = ActivityLog::forTenant($tenantId)
                ->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();

            // Get activity by hour (last 24 hours)
            $hourlyData = ActivityLog::forTenant($tenantId)
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subHours(24))
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->map(function ($item) {
                    return [
                        'hour' => $item->hour . ':00',
                        'count' => $item->count,
                    ];
                });

            // Get activity by day (last 30 days)
            $dailyData = ActivityLog::forTenant($tenantId)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'top_actions' => $topActions,
                    'hourly_data' => $hourlyData,
                    'daily_data' => $dailyData,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching activity stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics.',
            ], 500);
        }
    }

    /**
     * Clear activity logs older than specified days.
     */
    public function clean(Request $request)
    {
        try {
            $request->validate([
                'days' => ['required', 'integer', 'min:1', 'max:365'],
            ]);

            $tenantId = Auth::user()->tenant_id;
            $date = now()->subDays($request->days);

            $deleted = ActivityLog::forTenant($tenantId)
                ->where('created_at', '<', $date)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deleted} activity logs older than {$request->days} days.",
            ]);
        } catch (\Exception $e) {
            Log::error('Error cleaning activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clean activities: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get view preference.
     */
    public function setView(Request $request)
    {
        try {
            $request->validate([
                'view' => ['required', 'in:list,grid'],
            ]);

            session(['activity_view' => $request->view]);

            return response()->json([
                'success' => true,
                'message' => 'View updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update view.',
            ], 500);
        }
    }

    /**
     * Authorize that the activity belongs to the current tenant.
     */
    protected function authorizeTenant(ActivityLog $activity)
    {
        if ($activity->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Get icon for action type.
     */
    protected function getIconForAction(string $action): string
    {
        $icons = [
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash',
            'restored' => 'fa-undo',
            'viewed' => 'fa-eye',
            'logged_in' => 'fa-sign-in-alt',
            'logged_out' => 'fa-sign-out-alt',
            'status_changed' => 'fa-toggle-on',
            'password_changed' => 'fa-key',
            'image_uploaded' => 'fa-upload',
            'imported' => 'fa-file-import',
            'exported' => 'fa-file-export',
        ];

        return $icons[$action] ?? 'fa-circle';
    }

    /**
     * Get color for action type.
     */
    protected function getColorForAction(string $action): string
    {
        $colors = [
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'restored' => 'teal',
            'viewed' => 'gray',
            'logged_in' => 'indigo',
            'logged_out' => 'yellow',
            'status_changed' => 'purple',
            'password_changed' => 'orange',
            'image_uploaded' => 'pink',
            'imported' => 'cyan',
            'exported' => 'emerald',
        ];

        return $colors[$action] ?? 'gray';
    }
}