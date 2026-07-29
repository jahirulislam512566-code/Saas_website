<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Events\Event;

class OrderCancelled extends Event
{
    public Order $order;
    public string $reason;

    public function __construct(Order $order, string $reason = 'Customer requested cancellation')
    {
        parent::__construct();
        $this->order = $order;
        $this->reason = $reason;
    }

    public function eventName(): string
    {
        return 'order.cancelled';
    }

    public function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_id' => $this->order->customer_id,
            'total' => $this->order->total,
            'reason' => $this->reason,
            'cancelled_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}