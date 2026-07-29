<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignRoleAction
{
    /**
     * Assign role to user
     *
     * @param User $user
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $roles = $data['roles'] ?? [];
            $sync = $data['sync'] ?? true; // true = replace all, false = add only

            if (empty($roles)) {
                throw new \Exception('At least one role must be specified.');
            }

            $roleModels = Role::whereIn('name', $roles)->get();

            if ($roleModels->count() !== count($roles)) {
                $missingRoles = array_diff($roles, $roleModels->pluck('name')->toArray());
                throw new \Exception('Invalid roles: ' . implode(', ', $missingRoles));
            }

            if ($sync) {
                $user->syncRoles($roles);
            } else {
                $user->assignRole($roles);
            }

            // Clear permission cache
            $user->forgetCachedPermissions();

            Log::info('Roles assigned to user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'roles' => $roles,
                'action' => $sync ? 'sync' : 'assign',
                'assigned_by' => auth()->id()
            ]);

            return $user->fresh()->load(['roles', 'permissions']);
        });
    }
}