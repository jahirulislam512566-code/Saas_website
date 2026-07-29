<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteCustomerAction
{
    /**
     * Delete a customer (soft delete)
     *
     * @param Customer $customer
     * @param bool $forceDelete
     * @return bool
     */
    public function execute(Customer $customer, bool $forceDelete = false): bool
    {
        return DB::transaction(function () use ($customer, $forceDelete) {
            // Check if customer has related records
            if ($this->hasRelatedRecords($customer) && !$forceDelete) {
                throw new \Exception('Cannot delete customer with related records.');
            }

            if ($forceDelete) {
                $customer->forceDelete();
                Log::warning('Customer permanently deleted', [
                    'customer_id' => $customer->id,
                    'customer_number' => $customer->customer_number,
                    'deleted_by' => auth()->id()
                ]);
            } else {
                $customer->delete();
                Log::info('Customer soft deleted', [
                    'customer_id' => $customer->id,
                    'customer_number' => $customer->customer_number,
                    'deleted_by' => auth()->id()
                ]);
            }

            return true;
        });
    }

    /**
     * Check if customer has related records
     *
     * @param Customer $customer
     * @return bool
     */
    private function hasRelatedRecords(Customer $customer): bool
    {
        return $customer->invoices()->exists() ||
               $customer->projects()->exists() ||
               $customer->orders()->exists();
    }
}