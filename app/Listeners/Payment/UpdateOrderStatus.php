<?php

namespace App\Listeners\Payment;

use App\Events\Payment\PaymentReceived;
use App\Enums\OrderStatus;
use App\Listeners\BaseListener;

class UpdateOrderStatus extends BaseListener
{
    public function handle(PaymentReceived $event): void
    {
        $this->logStart('PaymentReceived', [
            'payment_id' => $event->payment->id,
            'order_id' => $event->payment->order_id,
        ]);

        try {
            $order = $event->payment->order;
            
            $order->update([
                'status' => OrderStatus::PROCESSING->value,
                'payment_status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $event->payment->payment_method,
            ]);

            $this->logComplete('PaymentReceived', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            $this->logError('PaymentReceived', $e, ['payment_id' => $event->payment->id]);
        }
    }
}