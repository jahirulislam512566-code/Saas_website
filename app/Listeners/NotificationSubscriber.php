<?php

namespace App\Listeners;

use App\Events\Customer\CustomerCreated;
use App\Events\Order\OrderCreated;
use App\Events\Payment\PaymentReceived;
use App\Events\Ticket\TicketCreated;
use App\Events\Project\ProjectCompleted;
use App\Events\Invoice\InvoiceOverdue;
use App\Notifications\Customer\WelcomeNotification;
use App\Notifications\Order\OrderConfirmationNotification;
use App\Notifications\Payment\PaymentReceivedNotification;
use App\Notifications\Ticket\TicketCreatedNotification;
use App\Notifications\Project\ProjectCompletedNotification;
use App\Notifications\Invoice\InvoiceOverdueNotification;
use Illuminate\Support\Facades\Notification;

class NotificationSubscriber
{
    /**
     * Handle customer created events.
     */
    public function handleCustomerCreated(CustomerCreated $event): void
    {
        // Send welcome notification
        Notification::send($event->customer, new WelcomeNotification($event->customer));
    }

    /**
     * Handle order created events.
     */
    public function handleOrderCreated(OrderCreated $event): void
    {
        // Send order confirmation
        Notification::send($event->order->customer, new OrderConfirmationNotification($event->order));
    }

    /**
     * Handle payment received events.
     */
    public function handlePaymentReceived(PaymentReceived $event): void
    {
        // Send payment confirmation
        Notification::send($event->payment->order->customer, new PaymentReceivedNotification($event->payment));
    }

    /**
     * Handle ticket created events.
     */
    public function handleTicketCreated(TicketCreated $event): void
    {
        // Send ticket acknowledgment
        Notification::send($event->ticket->customer, new TicketCreatedNotification($event->ticket));
    }

    /**
     * Handle project completed events.
     */
    public function handleProjectCompleted(ProjectCompleted $event): void
    {
        // Send project completion notification
        Notification::send($event->project->customer, new ProjectCompletedNotification($event->project));
    }

    /**
     * Handle invoice overdue events.
     */
    public function handleInvoiceOverdue(InvoiceOverdue $event): void
    {
        // Send overdue reminder
        Notification::send($event->invoice->customer, new InvoiceOverdueNotification($event->invoice));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): void
    {
        $events->listen(
            CustomerCreated::class,
            [NotificationSubscriber::class, 'handleCustomerCreated']
        );

        $events->listen(
            OrderCreated::class,
            [NotificationSubscriber::class, 'handleOrderCreated']
        );

        $events->listen(
            PaymentReceived::class,
            [NotificationSubscriber::class, 'handlePaymentReceived']
        );

        $events->listen(
            TicketCreated::class,
            [NotificationSubscriber::class, 'handleTicketCreated']
        );

        $events->listen(
            ProjectCompleted::class,
            [NotificationSubscriber::class, 'handleProjectCompleted']
        );

        $events->listen(
            InvoiceOverdue::class,
            [NotificationSubscriber::class, 'handleInvoiceOverdue']
        );
    }
}