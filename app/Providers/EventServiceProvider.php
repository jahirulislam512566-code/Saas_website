<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        // Auth Events
        \App\Events\Auth\UserRegistered::class => [
            \App\Listeners\Auth\SendWelcomeEmail::class,
            \App\Listeners\Auth\CreateUserProfile::class,
        ],
        \App\Events\Auth\UserLoggedIn::class => [
            \App\Listeners\Auth\LogLoginActivity::class,
            \App\Listeners\Auth\UpdateLastLogin::class,
        ],
        
        // Customer Events
        \App\Events\Customer\CustomerCreated::class => [
            \App\Listeners\Customer\SendWelcomeNotification::class,
            \App\Listeners\Customer\CreateDefaultSettings::class,
            \App\Listeners\Customer\AddToMailingList::class,
        ],
        \App\Events\Customer\CustomerSuspended::class => [
            \App\Listeners\Customer\SendSuspensionNotification::class,
            \App\Listeners\Customer\CancelActiveSubscriptions::class,
        ],
        
        // Order Events
        \App\Events\Order\OrderCreated::class => [
            \App\Listeners\Order\ProcessInventory::class,
            \App\Listeners\Order\SendOrderConfirmation::class,
            \App\Listeners\Order\NotifyFulfillmentTeam::class,
        ],
        \App\Events\Order\OrderCancelled::class => [
            \App\Listeners\Order\RestoreInventory::class,
            \App\Listeners\Order\ProcessRefund::class,
            \App\Listeners\Order\SendCancellationEmail::class,
        ],
        
        // Payment Events
        \App\Events\Payment\PaymentReceived::class => [
            \App\Listeners\Payment\UpdateOrderStatus::class,
            \App\Listeners\Payment\GenerateInvoice::class,
            \App\Listeners\Payment\SendReceiptEmail::class,
        ],
        \App\Events\Payment\PaymentFailed::class => [
            \App\Listeners\Payment\NotifyPaymentFailure::class,
            \App\Listeners\Payment\RetryPayment::class,
        ],
        
        // Invoice Events
        \App\Events\Invoice\InvoiceCreated::class => [
            \App\Listeners\Invoice\GeneratePDF::class,
            \App\Listeners\Invoice\AddToAccountingSystem::class,
        ],
        \App\Events\Invoice\InvoicePaid::class => [
            \App\Listeners\Invoice\UpdateAccountingRecords::class,
            \App\Listeners\Invoice\SendPaymentConfirmation::class,
        ],
        \App\Events\Invoice\InvoiceOverdue::class => [
            \App\Listeners\Invoice\SendOverdueReminder::class,
            \App\Listeners\Invoice\ApplyLateFee::class,
        ],
        
        // Ticket Events
        \App\Events\Ticket\TicketCreated::class => [
            \App\Listeners\Ticket\NotifySupportTeam::class,
            \App\Listeners\Ticket\AutoAssignTicket::class,
            \App\Listeners\Ticket\SendAcknowledgmentEmail::class,
        ],
        \App\Events\Ticket\TicketResolved::class => [
            \App\Listeners\Ticket\NotifyCustomerResolution::class,
            \App\Listeners\Ticket\UpdateTicketMetrics::class,
        ],
        
        // Project Events
        \App\Events\Project\ProjectCreated::class => [
            \App\Listeners\Project\CreateDefaultMilestones::class,
            \App\Listeners\Project\NotifyProjectTeam::class,
        ],
        \App\Events\Project\ProjectCompleted::class => [
            \App\Listeners\Project\GenerateCompletionReport::class,
            \App\Listeners\Project\NotifyStakeholders::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Register custom event subscribers
        Event::subscribe(\App\Listeners\AuditSubscriber::class);
        Event::subscribe(\App\Listeners\NotificationSubscriber::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}