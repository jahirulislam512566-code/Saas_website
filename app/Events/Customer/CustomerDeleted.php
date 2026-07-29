<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Events\Event;

class CustomerDeleted extends Event
{
    public Customer $customer;
    public bool $forceDelete;

    public function __construct(Customer $customer, bool $forceDelete = false)
    {
        parent::__construct();
        $this->customer = $customer;
        $this->forceDelete = $forceDelete;
    }

    public function eventName(): string
    {
        return 'customer.deleted';
    }

    public function payload(): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_number' => $this->customer->customer_number,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'force_delete' => $this->forceDelete,
            'deleted_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}