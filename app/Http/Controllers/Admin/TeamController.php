<?php
// app/Http/Controllers/Admin/TeamController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of teams.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Team::forTenant($tenantId)
                ->with(['owner', 'members']);

            // Search filter
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $allowedSorts = ['id', 'name', 'created_at', 'is_active'];
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $teams = $query->paginate(12)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Team::forTenant($tenantId)->count(),
                'active' => Team::forTenant($tenantId)->where('is_active', true)->count(),
                'inactive' => Team::forTenant($tenantId)->where('is_active', false)->count(),
                'total_members' => DB::table('team_members')->count(),
                'pending_invitations' => DB::table('team_invitations')->whereNull('accepted_at')->count(),
            ];

            // Get notification variables for top-nav
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.teams.index', compact(
                'teams',
                'stats',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching teams: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch teams. Please try again.');
        }
    }

    /**
     * Show the form for creating a new team.
     */
    public function create()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $users = User::forTenant($tenantId)->get();

            return view('admin.teams.create', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error loading create team form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create team form.');
        }
    }

    /**
     * Store a newly created team.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255', 'unique:teams'],
                'description' => ['nullable', 'string'],
                'owner_id' => ['required', 'exists:users,id'],
                'max_members' => ['nullable', 'integer', 'min:1'],
                'is_active' => ['nullable', 'boolean'],
                'members' => ['nullable', 'array'],
                'members.*.user_id' => ['exists:users,id'],
                'members.*.role' => ['in:admin,editor,member'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                // Generate slug if not provided
                $slug = $request->slug;
                if (empty($slug)) {
                    $slug = Str::slug($request->name);
                    $count = Team::where('slug', $slug)->count();
                    if ($count > 0) {
                        $slug = $slug . '-' . ($count + 1);
                    }
                }

                $team = Team::create([
                    'tenant_id' => $tenantId,
                    'owner_id' => $request->owner_id,
                    'name' => $request->name,
                    'slug' => $slug,
                    'description' => $request->description,
                    'max_members' => $request->max_members,
                    'is_active' => $request->has('is_active'),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                // Add owner as member
                $team->addMember(User::find($request->owner_id), 'admin');

                // Add additional members if provided
                if ($request->has('members')) {
                    foreach ($request->members as $member) {
                        if (!empty($member['user_id'])) {
                            $team->addMember(
                                User::find($member['user_id']),
                                $member['role'] ?? 'member'
                            );
                        }
                    }
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Team::class,
                    'subject_id' => $team->id,
                    'action' => 'created_team',
                    'description' => "Created team: {$team->name}",
                    'properties' => [
                        'team_name' => $team->name,
                        'team_slug' => $team->slug,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.teams.index')
                    ->with('success', "Team '{$team->name}' has been created successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating team: ' . $e->getMessage());
            return back()->with('error', 'Failed to create team. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team)
    {
        try {
            $this->authorizeTenant($team);
            
            $team->load(['owner', 'members']);

            return view('admin.teams.show', compact('team'));
        } catch (\Exception $e) {
            Log::error('Error showing team: ' . $e->getMessage());
            return back()->with('error', 'Unable to display team details.');
        }
    }

    /**
     * Show the form for editing the specified team.
     */
    public function edit(Team $team)
    {
        try {
            $this->authorizeTenant($team);
            
            $tenantId = auth()->user()->tenant_id;
            $users = User::forTenant($tenantId)->get();

            return view('admin.teams.edit', compact('team', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading edit team form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit team form.');
        }
    }

    /**
     * Update the specified team.
     */
    public function update(Request $request, Team $team)
    {
        try {
            $this->authorizeTenant($team);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:teams,slug,' . $team->id],
                'description' => ['nullable', 'string'],
                'owner_id' => ['required', 'exists:users,id'],
                'max_members' => ['nullable', 'integer', 'min:1'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $oldData = [
                    'name' => $team->name,
                    'owner_id' => $team->owner_id,
                    'is_active' => $team->is_active,
                ];

                $team->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'description' => $request->description,
                    'owner_id' => $request->owner_id,
                    'max_members' => $request->max_members,
                    'is_active' => $request->has('is_active'),
                    'updated_by' => auth()->id(),
                ]);

                // If owner changed, update owner's role
                if ($oldData['owner_id'] != $request->owner_id) {
                    // Remove old owner from team if they're not the same
                    if ($oldData['owner_id'] != $request->owner_id) {
                        $team->removeMember(User::find($oldData['owner_id']));
                    }
                    // Add new owner
                    $team->addMember(User::find($request->owner_id), 'admin');
                }

                // Log changes
                $changes = [];
                if ($oldData['name'] !== $request->name) $changes[] = 'name';
                if ($oldData['owner_id'] != $request->owner_id) $changes[] = 'owner';
                if ($oldData['is_active'] !== $request->has('is_active')) $changes[] = 'status';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Team::class,
                        'subject_id' => $team->id,
                        'action' => 'updated_team',
                        'description' => "Updated team: {$team->name}",
                        'properties' => [
                            'team_name' => $team->name,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.teams.index')
                    ->with('success', "Team '{$team->name}' has been updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating team: ' . $e->getMessage());
            return back()->with('error', 'Failed to update team. Please try again.')->withInput();
        }
    }

    /**
     * Delete the specified team.
     */
    public function destroy(Team $team)
    {
        try {
            $this->authorizeTenant($team);

            DB::beginTransaction();

            try {
                $teamName = $team->name;

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Team::class,
                    'subject_id' => $team->id,
                    'action' => 'deleted_team',
                    'description' => "Deleted team: {$teamName}",
                    'properties' => [
                        'team_name' => $teamName,
                        'team_slug' => $team->slug,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $team->delete();

                DB::commit();

                return redirect()->route('admin.teams.index')
                    ->with('success', "Team '{$teamName}' has been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting team: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete team. Please try again.');
        }
    }

    /**
     * Toggle team status.
     */
    public function toggle(Request $request, Team $team)
    {
        try {
            $this->authorizeTenant($team);

            $newStatus = !$team->is_active;
            $statusLabel = $newStatus ? 'activated' : 'deactivated';

            $team->update(['is_active' => $newStatus]);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Team::class,
                'subject_id' => $team->id,
                'action' => 'toggled_team',
                'description' => "{$statusLabel} team: {$team->name}",
                'properties' => [
                    'team_name' => $team->name,
                    'new_status' => $newStatus ? 'active' : 'inactive',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', "Team '{$team->name}' has been {$statusLabel}.");
        } catch (\Exception $e) {
            Log::error('Error toggling team: ' . $e->getMessage());
            return back()->with('error', 'Failed to change team status.');
        }
    }

    /**
     * Show team members.
     */
    public function members(Team $team)
    {
        try {
            $this->authorizeTenant($team);
            
            $members = $team->members()->paginate(15);
            $availableUsers = User::forTenant(auth()->user()->tenant_id)
                ->whereDoesntHave('teams', function ($q) use ($team) {
                    $q->where('team_id', $team->id);
                })
                ->get();

            return view('admin.teams.members', compact('team', 'members', 'availableUsers'));
        } catch (\Exception $e) {
            Log::error('Error loading team members: ' . $e->getMessage());
            return back()->with('error', 'Unable to load team members.');
        }
    }

    /**
     * Add a member to the team.
     */
    public function addMember(Request $request, Team $team)
    {
        try {
            $this->authorizeTenant($team);

            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'exists:users,id'],
                'role' => ['required', 'in:admin,editor,member'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::find($request->user_id);

            if ($team->isMember($user)) {
                return back()->with('error', 'User is already a member of this team.');
            }

            if ($team->is_full) {
                return back()->with('error', 'Team has reached its maximum member limit.');
            }

            $team->addMember($user, $request->role);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Team::class,
                'subject_id' => $team->id,
                'action' => 'added_team_member',
                'description' => "Added '{$user->name}' to team: {$team->name}",
                'properties' => [
                    'team_name' => $team->name,
                    'user_name' => $user->name,
                    'role' => $request->role,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.teams.members', $team)
                ->with('success', "User '{$user->name}' has been added to the team.");
        } catch (\Exception $e) {
            Log::error('Error adding team member: ' . $e->getMessage());
            return back()->with('error', 'Failed to add member to team.');
        }
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Request $request, Team $team, User $user)
    {
        try {
            $this->authorizeTenant($team);

            if ($team->owner_id == $user->id) {
                return back()->with('error', 'Cannot remove the team owner.');
            }

            $team->removeMember($user);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Team::class,
                'subject_id' => $team->id,
                'action' => 'removed_team_member',
                'description' => "Removed '{$user->name}' from team: {$team->name}",
                'properties' => [
                    'team_name' => $team->name,
                    'user_name' => $user->name,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.teams.members', $team)
                ->with('success', "User '{$user->name}' has been removed from the team.");
        } catch (\Exception $e) {
            Log::error('Error removing team member: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove member from team.');
        }
    }

    /**
     * Update a member's role.
     */
    public function updateMember(Request $request, Team $team, User $user)
    {
        try {
            $this->authorizeTenant($team);

            $validator = Validator::make($request->all(), [
                'role' => ['required', 'in:admin,editor,member'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            if ($team->owner_id == $user->id) {
                return back()->with('error', 'Cannot change the role of the team owner.');
            }

            $team->updateMemberRole($user, $request->role);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Team::class,
                'subject_id' => $team->id,
                'action' => 'updated_team_member_role',
                'description' => "Updated role for '{$user->name}' in team: {$team->name}",
                'properties' => [
                    'team_name' => $team->name,
                    'user_name' => $user->name,
                    'new_role' => $request->role,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.teams.members', $team)
                ->with('success', "Role for '{$user->name}' has been updated.");
        } catch (\Exception $e) {
            Log::error('Error updating team member role: ' . $e->getMessage());
            return back()->with('error', 'Failed to update member role.');
        }
    }

    /**
     * Export teams to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $teams = Team::forTenant($tenantId)->with(['owner', 'members'])->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="teams_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($teams) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'ID', 'Name', 'Slug', 'Owner', 'Members', 'Status', 'Created At'
                ]);

                foreach ($teams as $team) {
                    fputcsv($handle, [
                        $team->id,
                        $team->name,
                        $team->slug,
                        $team->owner->name ?? 'Unknown',
                        $team->members_count,
                        $team->is_active ? 'Active' : 'Inactive',
                        $team->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting teams: ' . $e->getMessage());
            return back()->with('error', 'Failed to export teams.');
        }
    }

    /**
     * Authorize that the team belongs to the current tenant.
     */
    protected function authorizeTenant(Team $team)
    {
        if ($team->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}