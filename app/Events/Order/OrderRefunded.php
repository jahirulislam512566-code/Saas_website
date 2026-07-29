<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Events\Event;

class OrderRefunded extends Event
{
    public Order $order;
    public float $amount;
    public string $reason;

    public function __construct(Order $order, float $amount, string $reason)
    {
        parent::__construct();
        $this->order = $order;
        $this->amount = $amount;
        $this->reason = $reason;
    }

    public function eventName(): string
    {
        return 'order.refunded';
    }

    public function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_id' => $this->order->customer_id,
            'refund_amount' => $this->amount,
            'reason' => $this->reason,
            'refunded_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}