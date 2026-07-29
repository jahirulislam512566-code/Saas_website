<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy extends BasePolicy
{
    /**
     * Determine if user can view any users.
     */
    public function viewAny(User $user): Response
    {
        return $this->adminAllowed($user);
    }

    /**
     * Determine if user can view a specific user.
     */
    public function view(User $user, User $model): Response
    {
        return $this->ownerAllowed($user, $model);
    }

    /**
     * Determine if user can create users.
     */
    public function create(User $user): Response
    {
        return $this->adminAllowed($user);
    }

    /**
     * Determine if user can update a user.
     */
    public function update(User $user, User $model): Response
    {
        // Super admin can update anyone
        if ($this->isSuperAdmin($user)) {
            return Response::allow();
        }

        // Admin can update non-super admin users
        if ($this->isAdmin($user) && !$model->hasRole('super_admin')) {
            return Response::allow();
        }

        // Users can update themselves
        if ($user->id === $model->id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this user.');
    }

    /**
     * Determine if user can delete a user.
     */
    public function delete(User $user, User $model): Response
    {
        // Cannot delete self
        if ($user->id === $model->id) {
            return Response::deny('You cannot delete yourself.');
        }

        // Super admin can delete anyone
        if ($this->isSuperAdmin($user)) {
            return Response::allow();
        }

        // Admin can delete non-super admin users
        if ($this->isAdmin($user) && !$model->hasRole('super_admin')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this user.');
    }

    /**
     * Determine if user can restore a user.
     */
    public function restore(User $user, User $model): Response
    {
        return $this->adminAllowed($user);
    }

    /**
     * Determine if user can force delete a user.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return $this->isSuperAdmin($user)
            ? Response::allow()
            : Response::deny('Only super admins can permanently delete users.');
    }

    /**
     * Determine if user can assign roles.
     */
    public function assignRoles(User $user, User $model): Response
    {
        // Super admin can assign any role
        if ($this->isSuperAdmin($user)) {
            return Response::allow();
        }

        // Admin can assign non-super admin roles
        if ($this->isAdmin($user) && !$model->hasRole('super_admin')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to assign roles.');
    }

    /**
     * Determine if user can view user's permissions.
     */
    public function viewPermissions(User $user, User $model): Response
    {
        return $this->ownerAllowed($user, $model);
    }

    /**
     * Determine if user can impersonate a user.
     */
    public function impersonate(User $user, User $model): Response
    {
        // Cannot impersonate self
        if ($user->id === $model->id) {
            return Response::deny('You cannot impersonate yourself.');
        }

        // Cannot impersonate super admin
        if ($model->hasRole('super_admin')) {
            return Response::deny('You cannot impersonate a super admin.');
        }

        return $this->isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to impersonate users.');
    }
}