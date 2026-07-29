<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Events\Event;

class CustomerUpdated extends Event
{
    public Customer $customer;
    public array $oldData;
    public array $newData;

    public function __construct(Customer $customer, array $oldData, array $newData)
    {
        parent::__construct();
        $this->customer = $customer;
        $this->oldData = $oldData;
        $this->newData = $newData;
    }

    public function eventName(): string
    {
        return 'customer.updated';
    }

    public function payload(): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_number' => $this->customer->customer_number,
            'customer_name' => $this->customer->name,
            'old_data' => $this->oldData,
            'new_data' => $this->newData,
            'changes' => array_diff_assoc($this->newData, $this->oldData),
            'updated_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}