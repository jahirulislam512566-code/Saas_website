<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Events\Event;

class OrderApproved extends Event
{
    public Order $order;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->order = $order;
    }

    public function eventName(): string
    {
        return 'order.approved';
    }

    public function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_id' => $this->order->customer_id,
            'total' => $this->order->total,
            'approved_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}