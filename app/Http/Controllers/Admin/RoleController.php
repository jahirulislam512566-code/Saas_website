<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        try {
            $query = Role::query();

            // Add withCount only if relationship exists
            try {
                $query->withCount(['users', 'permissions']);
            } catch (\Exception $e) {
                // If relationship doesn't exist, continue without counts
                Log::warning('Relationship counts not available: ' . $e->getMessage());
            }

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('display_name', 'LIKE', "%{$search}%");
                });
            }

            // Status filter - check if column exists first
            try {
                if ($request->filled('status')) {
                    $query->where('is_active', $request->status === 'active' ? 1 : 0);
                }
            } catch (\Exception $e) {
                // If is_active column doesn't exist, ignore status filter
                Log::warning('Status filter not available: ' . $e->getMessage());
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            
            // Check if column exists before ordering
            $allowedSorts = ['id', 'name', 'display_name', 'created_at'];
            try {
                $columns = \Schema::getColumnListing('roles');
                $allowedSorts = array_merge($allowedSorts, ['is_active']);
                $allowedSorts = array_intersect($allowedSorts, $columns);
            } catch (\Exception $e) {
                // If can't get columns, use defaults
                Log::warning('Could not get column listing: ' . $e->getMessage());
            }
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Paginate results
            $roles = $query->paginate(15)->withQueryString();

            // Get statistics safely
            try {
                $stats = [
                    'total' => Role::count(),
                    'active' => Role::where('is_active', true)->count(),
                    'inactive' => Role::where('is_active', false)->count(),
                    'total_users' => \App\Models\User::count(),
                    'total_permissions' => Permission::count(),
                ];
            } catch (\Exception $e) {
                // Fallback stats if columns don't exist
                $stats = [
                    'total' => Role::count(),
                    'active' => 0,
                    'inactive' => 0,
                    'total_users' => \App\Models\User::count(),
                    'total_permissions' => Permission::count(),
                ];
                Log::warning('Stats calculation fallback: ' . $e->getMessage());
            }

            return view('admin.roles.index', compact('roles', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return with empty data to prevent breaking the view
            $roles = collect([]);
            $stats = [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'total_users' => 0,
                'total_permissions' => 0,
            ];
            
            return view('admin.roles.index', compact('roles', 'stats'))
                ->with('error', 'Unable to fetch roles. Please check the error logs.');
        }
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        try {
            $permissions = Permission::all();
        } catch (\Exception $e) {
            $permissions = collect([]);
            Log::error('Error fetching permissions: ' . $e->getMessage());
        }
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
                'display_name' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,id'],
            ]);

            // Create role with proper guard
            $role = Role::create([
                'name' => $validated['name'],
                'display_name' => $validated['display_name'] ?? $validated['name'],
                'description' => $validated['description'] ?? null,
                'guard_name' => 'web',
                'is_active' => true,
            ]);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return back()->with('error', 'Unable to create role. ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        try {
            $role->load(['permissions', 'users']);
            return view('admin.roles.show', compact('role'));
        } catch (\Exception $e) {
            Log::error('Error showing role: ' . $e->getMessage());
            return redirect()->route('admin.roles.index')
                ->with('error', 'Unable to display role details.');
        }
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        try {
            $permissions = Permission::all();
            $rolePermissions = $role->permissions->pluck('id')->toArray();
            
            return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
        } catch (\Exception $e) {
            Log::error('Error editing role: ' . $e->getMessage());
            return redirect()->route('admin.roles.index')
                ->with('error', 'Unable to edit role.');
        }
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required', 
                    'string', 
                    'max:255',
                    Rule::unique('roles', 'name')->ignore($role->id),
                ],
                'display_name' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,id'],
            ]);

            // Check if role is protected
            if ($role->name === 'super-admin' && !auth()->user()->hasRole('super-admin')) {
                return back()->with('error', 'You cannot modify the Super Admin role.');
            }

            $role->update([
                'name' => $validated['name'],
                'display_name' => $validated['display_name'] ?? $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return back()->with('error', 'Unable to update role.')
                        ->withInput();
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        try {
            // Prevent deletion of system roles
            if ($role->name === 'super-admin' || $role->name === 'admin') {
                return back()->with('error', 'Cannot delete system roles.');
            }

            // Check if role has users assigned
            if ($role->users()->count() > 0) {
                return back()->with('error', 'Cannot delete role with assigned users.');
            }

            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete role.');
        }
    }

    /**
     * Toggle role status (active/inactive).
     */
    public function toggleStatus(Role $role)
    {
        try {
            // Check if column exists
            if (!\Schema::hasColumn('roles', 'is_active')) {
                return back()->with('error', 'is_active column does not exist in roles table.');
            }

            // Prevent disabling system roles
            if ($role->name === 'super-admin') {
                return back()->with('error', 'Cannot change status of Super Admin role.');
            }

            $role->update(['is_active' => !$role->is_active]);

            $status = $role->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Role {$status} successfully.");
        } catch (\Exception $e) {
            Log::error('Error toggling role status: ' . $e->getMessage());
            return back()->with('error', 'Unable to change role status.');
        }
    }

    /**
     * Get permissions for AJAX requests.
     */
    public function getPermissions(Request $request)
    {
        try {
            $permissions = Permission::all();
            return response()->json($permissions);
        } catch (\Exception $e) {
            Log::error('Error fetching permissions: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch permissions'], 500);
        }
    }
}