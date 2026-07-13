<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['roles']);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('company_name', 'LIKE', "%{$search}%");
                });
            }

            // Role filter
            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('id', $request->role);
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            
            $allowedSorts = ['id', 'name', 'email', 'created_at', 'updated_at', 'is_active'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $users = $query->paginate(15)->withQueryString();
            
            // Get all roles for filter dropdown
            $roles = Role::all();

            // Calculate stats
            $stats = [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'admins' => User::whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];

            return view('admin.users.index', compact('users', 'roles', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch users. Please try again.');
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('admin.users.create', compact('roles'));
        } catch (\Exception $e) {
            Log::error('Error loading create user form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create user form.');
        }
    }

    /**
     * Store a newly created user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'role' => ['required', 'exists:roles,id'],
                'is_active' => ['nullable', 'boolean'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Handle avatar upload
                $avatarPath = null;
                if ($request->hasFile('avatar')) {
                    $avatarPath = $request->file('avatar')->store('avatars', 'public');
                }

                // Create the user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'is_active' => $request->has('is_active'),
                    'avatar' => $avatarPath,
                    'email_verified_at' => now(),
                ]);

                // Assign role
                $role = Role::findOrFail($request->role);
                $user->roles()->attach($role);

                // Log the activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'action' => 'created_user',
                    'description' => "Created user: {$user->name}",
                    'properties' => [
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'role' => $role->name,
                        'status' => $request->has('is_active') ? 'active' : 'inactive',
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.users.index')
                    ->with('success', "User {$user->name} has been created successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->with('error', 'Failed to create user. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        try {
            $user->load(['roles', 'subscriptions.plan', 'websites', 'posts', 'comments', 'activities' => function ($query) {
                $query->latest()->limit(10);
            }]);

            // Calculate user statistics
            $stats = [
                'total_posts' => $user->posts()->count(),
                'total_comments' => $user->comments()->count(),
                'total_subscriptions' => $user->subscriptions()->count(),
                'active_subscriptions' => $user->subscriptions()->where('status', 'active')->count(),
                'total_websites' => $user->websites()->count(),
                'total_activities' => $user->activities()->count(),
            ];

            return view('admin.users.show', compact('user', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error showing user: ' . $e->getMessage());
            return back()->with('error', 'Unable to display user details.');
        }
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        try {
            $user->load('roles');
            $roles = Role::all();
            
            return view('admin.users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            Log::error('Error loading edit user form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit user form.');
        }
    }

    /**
     * Update the specified user.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'confirmed', Password::defaults()],
                'role' => ['required', 'exists:roles,id'],
                'is_active' => ['nullable', 'boolean'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $oldData = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->first()->id ?? null,
                    'status' => $user->is_active,
                    'avatar' => $user->avatar,
                ];

                $updateData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'is_active' => $request->has('is_active'),
                ];

                if ($request->filled('password')) {
                    $updateData['password'] = Hash::make($request->password);
                }

                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    // Delete old avatar
                    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
                }

                $user->update($updateData);
                $user->roles()->sync([$request->role]);

                // Log the activity
                $changes = [];
                if ($oldData['name'] !== $request->name) $changes[] = 'name';
                if ($oldData['email'] !== $request->email) $changes[] = 'email';
                if ($oldData['role'] != $request->role) $changes[] = 'role';
                if ($oldData['status'] != $request->has('is_active')) $changes[] = 'status';
                if ($oldData['avatar'] !== ($updateData['avatar'] ?? null)) $changes[] = 'avatar';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => User::class,
                        'subject_id' => $user->id,
                        'action' => 'updated_user',
                        'description' => "Updated user: {$user->name}",
                        'properties' => [
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.users.index')
                    ->with('success', "User {$user->name} has been updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->with('error', 'Failed to update user. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified user.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        try {
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            DB::beginTransaction();

            try {
                $userName = $user->name;

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'action' => 'deleted_user',
                    'description' => "Deleted user: {$userName}",
                    'properties' => [
                        'user_name' => $userName,
                        'user_email' => $user->email,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Delete avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $user->delete();

                DB::commit();

                return redirect()->route('admin.users.index')
                    ->with('success', "User {$userName} has been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Toggle user status.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Request $request, User $user)
    {
        try {
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot change your own status.');
            }

            DB::beginTransaction();

            try {
                $newStatus = !$user->is_active;
                $statusLabel = $newStatus ? 'activated' : 'deactivated';

                $user->update(['is_active' => $newStatus]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'action' => 'toggled_user_status',
                    'description' => "{$statusLabel} user: {$user->name}",
                    'properties' => [
                        'user_name' => $user->name,
                        'new_status' => $newStatus ? 'active' : 'inactive',
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return back()->with('success', "User {$user->name} has been {$statusLabel}.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());
            return back()->with('error', 'Failed to change user status. Please try again.');
        }
    }

    /**
     * Delete user avatar.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAvatar(Request $request, User $user)
    {
        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
                
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => User::class,
                    'subject_id' => $user->id,
                    'action' => 'deleted_avatar',
                    'description' => "Deleted avatar for user: {$user->name}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return back()->with('success', 'Profile photo has been removed.');
            }

            return back()->with('error', 'No profile photo found to delete.');
        } catch (\Exception $e) {
            Log::error('Error deleting avatar: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete profile photo.');
        }
    }

    /**
     * Bulk delete users.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'user_ids' => ['required', 'array'],
                'user_ids.*' => ['exists:users,id'],
            ]);

            $userIds = $request->user_ids;

            if (in_array(auth()->id(), $userIds)) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            DB::beginTransaction();

            try {
                $users = User::whereIn('id', $userIds)->get();
                $count = $users->count();

                foreach ($users as $user) {
                    // Delete avatar
                    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }

                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => User::class,
                        'subject_id' => $user->id,
                        'action' => 'bulk_deleted_user',
                        'description' => "Bulk deleted user: {$user->name}",
                        'properties' => [
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                User::whereIn('id', $userIds)->delete();

                DB::commit();

                return redirect()->route('admin.users.index')
                    ->with('success', "{$count} users have been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error bulk deleting users: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete selected users. Please try again.');
        }
    }

    /**
     * Bulk action for users.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'user_ids' => ['required', 'array'],
                'user_ids.*' => ['exists:users,id'],
                'action' => ['required', 'in:activate,deactivate,delete'],
            ]);

            $userIds = $request->user_ids;
            $action = $request->action;

            if (in_array(auth()->id(), $userIds) && $action === 'delete') {
                return back()->with('error', 'You cannot delete your own account.');
            }

            DB::beginTransaction();

            try {
                $users = User::whereIn('id', $userIds)->get();
                $count = $users->count();

                foreach ($users as $user) {
                    if ($action === 'activate') {
                        $user->update(['is_active' => true]);
                        $actionLabel = 'activated';
                    } elseif ($action === 'deactivate') {
                        $user->update(['is_active' => false]);
                        $actionLabel = 'deactivated';
                    } elseif ($action === 'delete') {
                        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                            Storage::disk('public')->delete($user->avatar);
                        }
                        $user->delete();
                        $actionLabel = 'deleted';
                    }

                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => User::class,
                        'subject_id' => $user->id,
                        'action' => "bulk_{$action}_user",
                        'description' => "Bulk {$actionLabel} user: {$user->name}",
                        'properties' => [
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'action' => $action,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                if ($action !== 'delete') {
                    $message = "{$count} users have been {$actionLabel} successfully.";
                } else {
                    $message = "{$count} users have been deleted successfully.";
                }

                DB::commit();

                return redirect()->route('admin.users.index')
                    ->with('success', $message);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error bulk action users: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }

    /**
     * Export users to CSV.
     *
     * @param Request $request
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request, $format = 'csv')
    {
        try {
            $users = User::with('roles')->get();

            $filename = 'users_' . date('Y-m-d') . '.' . $format;
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            if ($format === 'excel') {
                // For Excel export (using CSV with UTF-8 BOM)
                $headers['Content-Type'] = 'text/csv; charset=UTF-8';
            }

            $callback = function () use ($users) {
                $handle = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'ID', 'Name', 'Email', 'Company', 'Role', 'Status', 
                    'Email Verified', 'Joined Date', 'Last Login', 'Created At'
                ]);

                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->company_name ?? 'N/A',
                        $user->roles->first()->name ?? 'No Role',
                        $user->is_active ? 'Active' : 'Inactive',
                        $user->email_verified_at ? 'Yes' : 'No',
                        $user->created_at->format('Y-m-d'),
                        $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Never',
                        $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting users: ' . $e->getMessage());
            return back()->with('error', 'Failed to export users.');
        }
    }

    /**
     * Show trashed users.
     *
     * @return \Illuminate\View\View
     */
    public function trash()
    {
        try {
            $users = User::onlyTrashed()->with('roles')->paginate(15);
            return view('admin.users.trash', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error loading trashed users: ' . $e->getMessage());
            return back()->with('error', 'Unable to load trashed users.');
        }
    }

    /**
     * Restore a trashed user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $userName = $user->name;
            $user->restore();

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'action' => 'restored_user',
                'description' => "Restored user: {$userName}",
                'properties' => [
                    'user_name' => $userName,
                    'user_email' => $user->email,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.users.trash')
                ->with('success', "User {$userName} has been restored.");
        } catch (\Exception $e) {
            Log::error('Error restoring user: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore user. Please try again.');
        }
    }

    /**
     * Force delete a user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            $userName = $user->name;

            // Delete avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->forceDelete();

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'action' => 'force_deleted_user',
                'description' => "Permanently deleted user: {$userName}",
                'properties' => [
                    'user_name' => $userName,
                    'user_email' => $user->email,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.users.trash')
                ->with('success', "User {$userName} has been permanently deleted.");
        } catch (\Exception $e) {
            Log::error('Error force deleting user: ' . $e->getMessage());
            return back()->with('error', 'Failed to permanently delete user. Please try again.');
        }
    }

    /**
     * Show user profile.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function profile(User $user)
    {
        try {
            $user->load(['roles', 'subscriptions.plan']);
            return view('admin.users.profile', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading user profile: ' . $e->getMessage());
            return back()->with('error', 'Unable to load user profile.');
        }
    }

    /**
     * Show user subscriptions.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function subscriptions(User $user)
    {
        try {
            $subscriptions = $user->subscriptions()->with('plan')->paginate(10);
            return view('admin.users.subscriptions', compact('user', 'subscriptions'));
        } catch (\Exception $e) {
            Log::error('Error loading user subscriptions: ' . $e->getMessage());
            return back()->with('error', 'Unable to load user subscriptions.');
        }
    }

    /**
     * Search users (AJAX).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $users = User::where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->limit(10)
                ->get(['id', 'name', 'email', 'avatar']);

            // Format for select2 or similar
            $formatted = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'results' => $formatted,
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search users.',
            ], 500);
        }
    }

    /**
     * Show import form.
     *
     * @return \Illuminate\View\View
     */
    public function importForm()
    {
        return view('admin.users.import');
    }

    /**
     * Import users from CSV.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
            ]);

            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);

            // Validate header
            $requiredHeaders = ['name', 'email'];
            foreach ($requiredHeaders as $required) {
                if (!in_array($required, $header)) {
                    fclose($handle);
                    return back()->with('error', "CSV file missing required column: {$required}");
                }
            }

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    // Validate email uniqueness
                    if (User::where('email', $data['email'])->exists()) {
                        $errors[] = "Email {$data['email']} already exists. Skipping.";
                        continue;
                    }

                    // Create user
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password'] ?? 'password'),
                        'is_active' => ($data['status'] ?? 'active') === 'active',
                        'company_name' => $data['company_name'] ?? null,
                        'email_verified_at' => now(),
                    ]);

                    // Assign role if provided
                    if (!empty($data['role'])) {
                        $role = Role::where('name', $data['role'])->first();
                        if ($role) {
                            $user->roles()->attach($role);
                        }
                    }

                    $imported++;
                }

                fclose($handle);

                Activity::create([
                    'user_id' => auth()->id(),
                    'action' => 'imported_users',
                    'description' => "Imported {$imported} users",
                    'properties' => [
                        'imported_count' => $imported,
                        'errors_count' => count($errors),
                        'file_name' => $file->getClientOriginalName(),
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                $message = "Successfully imported {$imported} users.";
                if (!empty($errors)) {
                    $message .= " Errors: " . implode(' ', $errors);
                }

                return redirect()->route('admin.users.index')
                    ->with('success', $message);

            } catch (\Exception $e) {
                DB::rollBack();
                fclose($handle);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error importing users: ' . $e->getMessage());
            return back()->with('error', 'Failed to import users: ' . $e->getMessage());
        }
    }

    /**
     * Impersonate a user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(User $user)
    {
        try {
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot impersonate yourself.');
            }

            session()->put('impersonate', $user->id);
            
            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'action' => 'impersonated',
                'description' => "Impersonating user: {$user->name}",
                'properties' => [
                    'impersonated_user' => $user->name,
                    'impersonated_email' => $user->email,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Error impersonating user: ' . $e->getMessage());
            return back()->with('error', 'Failed to impersonate user.');
        }
    }

    /**
     * Stop impersonating.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stopImpersonate()
    {
        try {
            session()->forget('impersonate');
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            Log::error('Error stopping impersonation: ' . $e->getMessage());
            return back()->with('error', 'Failed to stop impersonation.');
        }
    }
}