<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreated;
use App\Mail\Order\OrderConfirmationMail;
use App\Listeners\BaseListener;
use App\Notifications\Order\OrderConfirmationNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendOrderConfirmation extends BaseListener
{
    public function handle(OrderCreated $event): void
    {
        $this->logStart('OrderCreated', ['order_id' => $event->order->id]);

        try {
            $customer = $event->order->customer;
            
            // Send email
            Mail::to($customer->email)
                ->send(new OrderConfirmationMail($event->order));
            
            // Send database notification
            Notification::send($customer, new OrderConfirmationNotification($event->order));

            // Send SMS if phone available
            if ($customer->phone) {
                // Send SMS notification
                // Notification::send($customer, new OrderConfirmationSMS($event->order));
            }

            $this->logComplete('OrderCreated', ['order_id' => $event->order->id]);
        } catch (\Exception $e) {
            $this->logError('OrderCreated', $e, ['order_id' => $event->order->id]);
        }
    }
}