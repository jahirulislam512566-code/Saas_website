<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Events\Event;

class OrderShipped extends Event
{
    public Order $order;
    public array $shippingDetails;

    public function __construct(Order $order, array $shippingDetails)
    {
        parent::__construct();
        $this->order = $order;
        $this->shippingDetails = $shippingDetails;
    }

    public function eventName(): string
    {
        return 'order.shipped';
    }

    public function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_id' => $this->order->customer_id,
            'shipping_details' => $this->shippingDetails,
            'shipped_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}