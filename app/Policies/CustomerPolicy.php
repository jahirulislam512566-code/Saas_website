<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\Response;

class CustomerPolicy extends BasePolicy
{
    /**
     * Determine if user can view any customers.
     */
    public function viewAny(User $user): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('view_customers')
            ? Response::allow()
            : Response::deny('You do not have permission to view customers.');
    }

    /**
     * Determine if user can view a specific customer.
     */
    public function view(User $user, Customer $customer): Response
    {
        // Admin can view any customer
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Customer can view their own profile
        if ($user->customer_id === $customer->id) {
            return Response::allow();
        }

        // Users with permission can view customers
        if ($user->hasPermission('view_customers')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this customer.');
    }

    /**
     * Determine if user can create customers.
     */
    public function create(User $user): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('create_customers')
            ? Response::allow()
            : Response::deny('You do not have permission to create customers.');
    }

    /**
     * Determine if user can update a customer.
     */
    public function update(User $user, Customer $customer): Response
    {
        // Admin can update any customer
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Customer can update their own profile
        if ($user->customer_id === $customer->id) {
            return Response::allow();
        }

        // Users with permission can update customers
        if ($user->hasPermission('edit_customers')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this customer.');
    }

    /**
     * Determine if user can delete a customer.
     */
    public function delete(User $user, Customer $customer): Response
    {
        // Admin can delete any customer
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Users with permission can delete customers
        if ($user->hasPermission('delete_customers')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this customer.');
    }

    /**
     * Determine if user can suspend a customer.
     */
    public function suspend(User $user, Customer $customer): Response
    {
        // Admin can suspend any customer
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Users with permission can suspend customers
        if ($user->hasPermission('suspend_customers')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to suspend this customer.');
    }

    /**
     * Determine if user can activate a customer.
     */
    public function activate(User $user, Customer $customer): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('activate_customers')
            ? Response::allow()
            : Response::deny('You do not have permission to activate customers.');
    }

    /**
     * Determine if user can deactivate a customer.
     */
    public function deactivate(User $user, Customer $customer): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('deactivate_customers')
            ? Response::allow()
            : Response::deny('You do not have permission to deactivate customers.');
    }
}