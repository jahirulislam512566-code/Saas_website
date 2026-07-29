<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use App\Notifications\CustomerActivatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ActivateCustomerAction
{
    /**
     * Activate a customer
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function execute(Customer $customer, array $data = []): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $oldStatus = $customer->status;

            $customer->update([
                'status' => 'active',
                'suspended_at' => null,
                'suspension_reason' => null,
                'suspension_notes' => null,
                'activated_at' => now(),
                'activation_notes' => $data['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // Send notification to customer
            if ($data['notify_customer'] ?? true) {
                Notification::send($customer, new CustomerActivatedNotification($customer));
            }

            Log::info('Customer activated', [
                'customer_id' => $customer->id,
                'customer_number' => $customer->customer_number,
                'old_status' => $oldStatus,
                'new_status' => 'active',
                'activated_by' => auth()->id()
            ]);

            return $customer->fresh();
        });
    }
}