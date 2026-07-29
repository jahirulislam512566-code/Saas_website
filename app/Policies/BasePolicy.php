<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

abstract class BasePolicy
{
    /**
     * Check if user is admin.
     */
    protected function isAdmin(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super_admin');
    }

    /**
     * Check if user is super admin.
     */
    protected function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Check if user owns the resource.
     */
    protected function isOwner(User $user, $resource): bool
    {
        if (method_exists($resource, 'user_id')) {
            return $user->id === $resource->user_id;
        }
        
        if (method_exists($resource, 'customer_id')) {
            return $user->customer_id === $resource->customer_id;
        }

        return false;
    }

    /**
     * Return admin allowed response.
     */
    protected function adminAllowed(User $user): Response
    {
        return $this->isAdmin($user) 
            ? Response::allow() 
            : Response::deny('Admin privileges required.');
    }

    /**
     * Return owner allowed response.
     */
    protected function ownerAllowed(User $user, $resource): Response
    {
        return $this->isOwner($user, $resource) || $this->isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not own this resource.');
    }
}