<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateCustomerAction
{
    /**
     * Create a new customer
     *
     * @param array $data
     * @return Customer
     */
    public function execute(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            // Generate customer number if needed
            $data['customer_number'] = $data['customer_number'] ?? $this->generateCustomerNumber();
            
            $customer = Customer::create([
                'customer_number' => $data['customer_number'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'company' => $data['company'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'country' => $data['country'] ?? 'US',
                'status' => 'active',
                'created_by' => auth()->id(),
            ]);

            Log::info('Customer created', [
                'customer_id' => $customer->id,
                'customer_number' => $customer->customer_number,
                'created_by' => auth()->id()
            ]);

            return $customer;
        });
    }

    /**
     * Generate unique customer number
     *
     * @return string
     */
    private function generateCustomerNumber(): string
    {
        $prefix = 'CUST';
        $year = date('Y');
        $lastCustomer = Customer::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastCustomer ? intval(substr($lastCustomer->customer_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $year, $number);
    }
}