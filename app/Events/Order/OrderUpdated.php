<?php

namespace App\Events\Order;

use App\Models\Order;
use App\Events\Event;

class OrderUpdated extends Event
{
    public Order $order;
    public array $oldStatus;
    public array $newStatus;

    public function __construct(Order $order, array $oldStatus, array $newStatus)
    {
        parent::__construct();
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function eventName(): string
    {
        return 'order.updated';
    }

    public function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changes' => array_diff_assoc($this->newStatus, $this->oldStatus),
            'updated_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}