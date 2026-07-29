<?php

namespace App\Listeners;

use App\Events\Customer\CustomerCreated;
use App\Events\Customer\CustomerUpdated;
use App\Events\Customer\CustomerDeleted;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderCancelled;
use App\Events\Payment\PaymentReceived;
use App\Events\Payment\PaymentFailed;
use App\Events\Project\ProjectCreated;
use App\Events\Project\ProjectUpdated;
use App\Events\Project\ProjectCompleted;
use App\Events\Invoice\InvoiceCreated;
use App\Events\Invoice\InvoicePaid;
use App\Events\Ticket\TicketCreated;
use App\Events\Ticket\TicketResolved;
use App\Events\Auth\UserRegistered;
use App\Events\Auth\UserLoggedIn;
use App\Events\Auth\UserLoggedOut;
use Illuminate\Support\Facades\Log;

class AuditSubscriber
{
    /**
     * Handle customer created events.
     */
    public function handleCustomerCreated(CustomerCreated $event): void
    {
        Log::channel('audit')->info('Customer Created', [
            'customer_id' => $event->customer->id,
            'customer_name' => $event->customer->name,
            'customer_email' => $event->customer->email,
            'created_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle customer updated events.
     */
    public function handleCustomerUpdated(CustomerUpdated $event): void
    {
        Log::channel('audit')->info('Customer Updated', [
            'customer_id' => $event->customer->id,
            'customer_name' => $event->customer->name,
            'changes' => $event->changes ?? [],
            'updated_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle customer deleted events.
     */
    public function handleCustomerDeleted(CustomerDeleted $event): void
    {
        Log::channel('audit')->warning('Customer Deleted', [
            'customer_id' => $event->customer->id,
            'customer_name' => $event->customer->name,
            'customer_email' => $event->customer->email,
            'deleted_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle order created events.
     */
    public function handleOrderCreated(OrderCreated $event): void
    {
        Log::channel('audit')->info('Order Created', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'customer_id' => $event->order->customer_id,
            'total' => $event->order->total,
            'created_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle order updated events.
     */
    public function handleOrderUpdated(OrderUpdated $event): void
    {
        Log::channel('audit')->info('Order Updated', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'old_status' => $event->oldStatus ?? null,
            'new_status' => $event->newStatus ?? null,
            'updated_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle order cancelled events.
     */
    public function handleOrderCancelled(OrderCancelled $event): void
    {
        Log::channel('audit')->warning('Order Cancelled', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'customer_id' => $event->order->customer_id,
            'reason' => $event->reason ?? 'No reason provided',
            'cancelled_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle payment received events.
     */
    public function handlePaymentReceived(PaymentReceived $event): void
    {
        Log::channel('audit')->info('Payment Received', [
            'payment_id' => $event->payment->id,
            'order_id' => $event->payment->order_id,
            'amount' => $event->payment->amount,
            'currency' => $event->payment->currency,
            'payment_method' => $event->payment->payment_method,
            'transaction_id' => $event->payment->gateway_reference,
            'received_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle payment failed events.
     */
    public function handlePaymentFailed(PaymentFailed $event): void
    {
        Log::channel('audit')->error('Payment Failed', [
            'payment_id' => $event->payment->id,
            'order_id' => $event->payment->order_id,
            'amount' => $event->payment->amount,
            'error' => $event->error,
            'failed_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle project created events.
     */
    public function handleProjectCreated(ProjectCreated $event): void
    {
        Log::channel('audit')->info('Project Created', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'project_type' => $event->project->project_type,
            'customer_id' => $event->project->customer_id,
            'created_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle project updated events.
     */
    public function handleProjectUpdated(ProjectUpdated $event): void
    {
        Log::channel('audit')->info('Project Updated', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'changes' => $event->changes ?? [],
            'updated_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle project completed events.
     */
    public function handleProjectCompleted(ProjectCompleted $event): void
    {
        Log::channel('audit')->info('Project Completed', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'completed_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle invoice created events.
     */
    public function handleInvoiceCreated(InvoiceCreated $event): void
    {
        Log::channel('audit')->info('Invoice Created', [
            'invoice_id' => $event->invoice->id,
            'invoice_number' => $event->invoice->invoice_number,
            'customer_id' => $event->invoice->customer_id,
            'total' => $event->invoice->total,
            'created_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle invoice paid events.
     */
    public function handleInvoicePaid(InvoicePaid $event): void
    {
        Log::channel('audit')->info('Invoice Paid', [
            'invoice_id' => $event->invoice->id,
            'invoice_number' => $event->invoice->invoice_number,
            'paid_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle ticket created events.
     */
    public function handleTicketCreated(TicketCreated $event): void
    {
        Log::channel('audit')->info('Ticket Created', [
            'ticket_id' => $event->ticket->id,
            'ticket_number' => $event->ticket->ticket_number,
            'subject' => $event->ticket->subject,
            'customer_id' => $event->ticket->customer_id,
            'priority' => $event->ticket->priority,
            'created_by' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle ticket resolved events.
     */
    public function handleTicketResolved(TicketResolved $event): void
    {
        Log::channel('audit')->info('Ticket Resolved', [
            'ticket_id' => $event->ticket->id,
            'ticket_number' => $event->ticket->ticket_number,
            'resolved_by' => auth()->id(),
            'resolution' => $event->resolution ?? '',
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle user registered events.
     */
    public function handleUserRegistered(UserRegistered $event): void
    {
        Log::channel('audit')->info('User Registered', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle user logged in events.
     */
    public function handleUserLoggedIn(UserLoggedIn $event): void
    {
        Log::channel('audit')->info('User Logged In', [
            'user_id' => $event->user->id,
            'user_email' => $event->user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle user logged out events.
     */
    public function handleUserLoggedOut(UserLoggedOut $event): void
    {
        Log::channel('audit')->info('User Logged Out', [
            'user_id' => $event->user->id,
            'user_email' => $event->user->email,
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): void
    {
        $events->listen(
            CustomerCreated::class,
            [AuditSubscriber::class, 'handleCustomerCreated']
        );

        $events->listen(
            CustomerUpdated::class,
            [AuditSubscriber::class, 'handleCustomerUpdated']
        );

        $events->listen(
            CustomerDeleted::class,
            [AuditSubscriber::class, 'handleCustomerDeleted']
        );

        $events->listen(
            OrderCreated::class,
            [AuditSubscriber::class, 'handleOrderCreated']
        );

        $events->listen(
            OrderUpdated::class,
            [AuditSubscriber::class, 'handleOrderUpdated']
        );

        $events->listen(
            OrderCancelled::class,
            [AuditSubscriber::class, 'handleOrderCancelled']
        );

        $events->listen(
            PaymentReceived::class,
            [AuditSubscriber::class, 'handlePaymentReceived']
        );

        $events->listen(
            PaymentFailed::class,
            [AuditSubscriber::class, 'handlePaymentFailed']
        );

        $events->listen(
            ProjectCreated::class,
            [AuditSubscriber::class, 'handleProjectCreated']
        );

        $events->listen(
            ProjectUpdated::class,
            [AuditSubscriber::class, 'handleProjectUpdated']
        );

        $events->listen(
            ProjectCompleted::class,
            [AuditSubscriber::class, 'handleProjectCompleted']
        );

        $events->listen(
            InvoiceCreated::class,
            [AuditSubscriber::class, 'handleInvoiceCreated']
        );

        $events->listen(
            InvoicePaid::class,
            [AuditSubscriber::class, 'handleInvoicePaid']
        );

        $events->listen(
            TicketCreated::class,
            [AuditSubscriber::class, 'handleTicketCreated']
        );

        $events->listen(
            TicketResolved::class,
            [AuditSubscriber::class, 'handleTicketResolved']
        );

        $events->listen(
            UserRegistered::class,
            [AuditSubscriber::class, 'handleUserRegistered']
        );

        $events->listen(
            UserLoggedIn::class,
            [AuditSubscriber::class, 'handleUserLoggedIn']
        );

        $events->listen(
            UserLoggedOut::class,
            [AuditSubscriber::class, 'handleUserLoggedOut']
        );
    }
}