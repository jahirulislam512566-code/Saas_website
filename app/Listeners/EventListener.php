<?php

namespace App\Listeners;

use App\Events\Customer\CustomerCreated;
use App\Events\Order\OrderCreated;
use App\Events\Payment\PaymentReceived;
use App\Events\Ticket\TicketCreated;
use Illuminate\Support\Facades\Log;

class EventListener
{
    /**
     * Handle customer created event
     */
    public function handleCustomerCreated(CustomerCreated $event): void
    {
        Log::info('New customer registered', $event->payload());
        
        // Send welcome email
        // Send welcome notification
        // Create default settings
        // Add to mailing list
    }

    /**
     * Handle order created event
     */
    public function handleOrderCreated(OrderCreated $event): void
    {
        Log::info('New order created', $event->payload());
        
        // Process inventory
        // Send confirmation email
        // Notify fulfillment team
        // Apply discounts or promotions
    }

    /**
     * Handle payment received event
     */
    public function handlePaymentReceived(PaymentReceived $event): void
    {
        Log::info('Payment received', $event->payload());
        
        // Update order status
        // Generate invoice
        // Send receipt email
        // Update CRM
    }

    /**
     * Handle ticket created event
     */
    public function handleTicketCreated(TicketCreated $event): void
    {
        Log::info('New support ticket created', $event->payload());
        
        // Notify support team
        // Auto-assign ticket
        // Send acknowledgment
        // Check for similar tickets
    }
}