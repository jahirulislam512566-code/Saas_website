<?php

namespace App\Observers;

use App\Models\Customer;
use App\Enums\CustomerStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomerObserver extends BaseObserver
{
    /**
     * Handle the Customer "creating" event.
     */
    public function creating(Customer $customer): void
    {
        // Generate customer number
        if (empty($customer->customer_number)) {
            $customer->customer_number = $this->generateCustomerNumber();
        }

        // Set default status
        if (empty($customer->status)) {
            $customer->status = CustomerStatus::ACTIVE->value;
        }

        $this->log('creating', $customer);
    }

    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        // Clear cache
        Cache::forget('customers_count');
        Cache::forget('customers_list');

        // Create audit log
        $this->createAuditLog($customer, 'created');

        $this->log('created', $customer);
    }

    /**
     * Handle the Customer "updating" event.
     */
    public function updating(Customer $customer): void
    {
        // Track status changes
        if ($customer->isDirty('status')) {
            $oldStatus = $customer->getOriginal('status');
            $newStatus = $customer->status;
            
            $this->logStatusChange($customer, $oldStatus, $newStatus);
        }

        $this->log('updating', $customer);
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        // Clear cache
        Cache::forget("customer_{$customer->id}");
        Cache::forget("customer_{$customer->email}");
        Cache::forget('customers_list');

        // Create audit log
        $this->createAuditLog($customer, 'updated', $customer->getChanges());

        $this->log('updated', $customer);
    }

    /**
     * Handle the Customer "deleting" event.
     */
    public function deleting(Customer $customer): void
    {
        // Check if customer has active orders
        if ($customer->orders()->whereIn('status', ['pending', 'processing'])->exists()) {
            throw new \Exception('Cannot delete customer with active orders.');
        }

        $this->log('deleting', $customer);
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        // Clear cache
        Cache::forget("customer_{$customer->id}");
        Cache::forget('customers_count');
        Cache::forget('customers_list');

        // Create audit log
        $this->createAuditLog($customer, 'deleted');

        $this->log('deleted', $customer);
    }

    /**
     * Generate unique customer number.
     */
    protected function generateCustomerNumber(): string
    {
        $prefix = 'CUST';
        $year = date('Y');
        $lastCustomer = Customer::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastCustomer ? intval(substr($lastCustomer->customer_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $year, $number);
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(Customer $customer, string $oldStatus, string $newStatus): void
    {
        Log::info('Customer status changed', [
            'customer_id' => $customer->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Customer $customer, string $action, array $changes = []): void
    {
        // Create audit log entry
        // \App\Models\AuditLog::create([...]);
    }
}