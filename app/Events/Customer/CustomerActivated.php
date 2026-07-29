<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Events\Event;

class CustomerActivated extends Event
{
    public Customer $customer;

    public function __construct(Customer $customer)
    {
        parent::__construct();
        $this->customer = $customer;
    }

    public function eventName(): string
    {
        return 'customer.activated';
    }

    public function payload(): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_number' => $this->customer->customer_number,
            'customer_name' => $this->customer->name,
            'activated_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}