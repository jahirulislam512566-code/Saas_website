<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Events\Event;

class OrderCreated extends Event
{
    public Order $order;
    public array $data;

    public function __construct(Order $order, array $data = [])
    {
        parent::__construct();
        $this->order = $order;
        $this->data = $data;
    }

    public function eventName(): string
    {
        return 'order.created';
    }

    public function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_id' => $this->order->customer_id,
            'total' => $this->order->total,
            'items_count' => $this->order->items->count(),
            'data' => $this->data,
            'created_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}