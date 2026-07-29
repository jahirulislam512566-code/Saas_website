<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCustomerAction
{
    /**
     * Update an existing customer
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function execute(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $oldData = $customer->toArray();

            $customer->update([
                'name' => $data['name'] ?? $customer->name,
                'email' => $data['email'] ?? $customer->email,
                'phone' => $data['phone'] ?? $customer->phone,
                'company' => $data['company'] ?? $customer->company,
                'address' => $data['address'] ?? $customer->address,
                'city' => $data['city'] ?? $customer->city,
                'state' => $data['state'] ?? $customer->state,
                'postal_code' => $data['postal_code'] ?? $customer->postal_code,
                'country' => $data['country'] ?? $customer->country,
                'updated_by' => auth()->id(),
            ]);

            Log::info('Customer updated', [
                'customer_id' => $customer->id,
                'customer_number' => $customer->customer_number,
                'updated_by' => auth()->id(),
                'changes' => array_diff_assoc($customer->toArray(), $oldData)
            ]);

            return $customer->fresh();
        });
    }
}