<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use App\Notifications\CustomerSuspendedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SuspendCustomerAction
{
    /**
     * Suspend a customer
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function execute(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $oldStatus = $customer->status;

            $customer->update([
                'status' => 'suspended',
                'suspended_at' => now(),
                'suspension_reason' => $data['reason'] ?? 'No reason provided',
                'suspension_notes' => $data['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // Send notification to customer
            if ($data['notify_customer'] ?? true) {
                Notification::send($customer, new CustomerSuspendedNotification(
                    $customer,
                    $data['reason'] ?? 'No reason provided'
                ));
            }

            Log::warning('Customer suspended', [
                'customer_id' => $customer->id,
                'customer_number' => $customer->customer_number,
                'old_status' => $oldStatus,
                'new_status' => 'suspended',
                'reason' => $data['reason'] ?? 'No reason provided',
                'suspended_by' => auth()->id()
            ]);

            return $customer->fresh();
        });
    }
}