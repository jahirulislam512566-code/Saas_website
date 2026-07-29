<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\Response;

class OrderPolicy extends BasePolicy
{
    /**
     * Determine if user can view any orders.
     */
    public function viewAny(User $user): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('view_orders')
            ? Response::allow()
            : Response::deny('You do not have permission to view orders.');
    }

    /**
     * Determine if user can view a specific order.
     */
    public function view(User $user, Order $order): Response
    {
        // Admin can view any order
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Customer can view their own orders
        if ($user->customer_id === $order->customer_id) {
            return Response::allow();
        }

        // Users with permission can view orders
        if ($user->hasPermission('view_orders')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this order.');
    }

    /**
     * Determine if user can create orders.
     */
    public function create(User $user): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('create_orders')
            ? Response::allow()
            : Response::deny('You do not have permission to create orders.');
    }

    /**
     * Determine if user can update an order.
     */
    public function update(User $user, Order $order): Response
    {
        // Admin can update any order
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Users with permission can update orders
        if ($user->hasPermission('edit_orders')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this order.');
    }

    /**
     * Determine if user can cancel an order.
     */
    public function cancel(User $user, Order $order): Response
    {
        // Admin can cancel any order
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Customer can cancel their own pending orders
        if ($user->customer_id === $order->customer_id && $order->status === 'pending') {
            return Response::allow();
        }

        // Users with permission can cancel orders
        if ($user->hasPermission('cancel_orders')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to cancel this order.');
    }

    /**
     * Determine if user can refund an order.
     */
    public function refund(User $user, Order $order): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('refund_orders')
            ? Response::allow()
            : Response::deny('You do not have permission to refund orders.');
    }

    /**
     * Determine if user can approve an order.
     */
    public function approve(User $user, Order $order): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('approve_orders')
            ? Response::allow()
            : Response::deny('You do not have permission to approve orders.');
    }

    /**
     * Determine if user can download order invoice.
     */
    public function downloadInvoice(User $user, Order $order): Response
    {
        // Admin can download any invoice
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Customer can download their own invoice
        if ($user->customer_id === $order->customer_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to download this invoice.');
    }
}