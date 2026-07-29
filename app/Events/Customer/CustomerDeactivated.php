<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Events\Event;

class CustomerDeactivated extends Event
{
    public Customer $customer;
    public string $reason;

    public function __construct(Customer $customer, string $reason = 'No reason provided')
    {
        parent::__construct();
        $this->customer = $customer;
        $this->reason = $reason;
    }

    public function eventName(): string
    {
        return 'customer.deactivated';
    }

    public function payload(): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_number' => $this->customer->customer_number,
            'customer_name' => $this->customer->name,
            'reason' => $this->reason,
            'deactivated_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}