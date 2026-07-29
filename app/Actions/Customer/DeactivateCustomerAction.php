<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use App\Notifications\CustomerDeactivatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DeactivateCustomerAction
{
    /**
     * Deactivate a customer (soft block)
     * 
     * Different from suspend - deactivation is a softer approach,
     * typically used for inactive accounts rather than punitive measures
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     * @throws \Exception
     */
    public function execute(Customer $customer, array $data = []): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            // Check if customer is already deactivated
            if ($customer->status === 'deactivated') {
                Log::info('Customer already deactivated', [
                    'customer_id' => $customer->id,
                    'customer_number' => $customer->customer_number,
                    'attempted_by' => auth()->id()
                ]);
                return $customer;
            }

            // Validate deactivation
            $this->validateDeactivation($customer, $data);

            $oldStatus = $customer->status;
            $oldData = $customer->toArray();

            // Perform deactivation
            $customer->update([
                'status' => 'deactivated',
                'deactivated_at' => now(),
                'deactivation_reason' => $data['reason'] ?? 'No reason provided',
                'deactivation_notes' => $data['notes'] ?? null,
                'deactivated_by' => auth()->id(),
                'can_reactivate' => $data['can_reactivate'] ?? true,
                'auto_reactivate_at' => $data['auto_reactivate_at'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // Handle related records if specified
            if ($data['handle_related'] ?? true) {
                $this->handleRelatedRecords($customer, $data);
            }

            // Send notification
            if ($data['notify_customer'] ?? true) {
                $this->sendNotification($customer, $data);
            }

            // Log deactivation
            Log::warning('Customer deactivated', [
                'customer_id' => $customer->id,
                'customer_number' => $customer->customer_number,
                'old_status' => $oldStatus,
                'new_status' => 'deactivated',
                'reason' => $data['reason'] ?? 'No reason provided',
                'deactivated_by' => auth()->id(),
                'changes' => array_diff_assoc($customer->toArray(), $oldData)
            ]);

            // Dispatch event
            if (class_exists('App\Events\CustomerDeactivated')) {
                event(new \App\Events\CustomerDeactivated($customer, $data));
            }

            return $customer->fresh();
        });
    }

    /**
     * Validate if customer can be deactivated
     *
     * @param Customer $customer
     * @param array $data
     * @throws \Exception
     */
    private function validateDeactivation(Customer $customer, array $data): void
    {
        // Check if customer has active subscriptions
        if ($customer->subscriptions()->where('status', 'active')->exists()) {
            throw new \Exception('Cannot deactivate customer with active subscriptions. Please cancel subscriptions first.');
        }

        // Check for pending invoices
        if ($customer->invoices()->whereIn('status', ['pending', 'overdue'])->exists()) {
            throw new \Exception('Cannot deactivate customer with pending or overdue invoices.');
        }

        // Check for active projects
        if ($customer->projects()->whereIn('status', ['in_progress', 'on_hold'])->exists()) {
            if (!($data['force'] ?? false)) {
                throw new \Exception('Customer has active projects. Use force=true to override.');
            }
        }

        // Check if deactivation is allowed by business rules
        if (isset($data['check_business_rules']) && $data['check_business_rules'] === true) {
            $this->checkBusinessRules($customer);
        }
    }

    /**
     * Check business rules before deactivation
     *
     * @param Customer $customer
     * @throws \Exception
     */
    private function checkBusinessRules(Customer $customer): void
    {
        // Example: Minimum account age before deactivation
        if ($customer->created_at->diffInDays(now()) < 30) {
            throw new \Exception('Cannot deactivate account that is less than 30 days old.');
        }

        // Example: Check for minimum balance
        if ($customer->balance > 0) {
            throw new \Exception('Customer has outstanding balance. Please resolve before deactivation.');
        }
    }

    /**
     * Handle related records when deactivating
     *
     * @param Customer $customer
     * @param array $data
     */
    private function handleRelatedRecords(Customer $customer, array $data): void
    {
        // Deactivate customer's projects
        if ($data['deactivate_projects'] ?? true) {
            $customer->projects()->whereIn('status', ['in_progress', 'on_hold'])
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Customer deactivated'
                ]);
        }

        // Cancel any pending orders
        if ($data['cancel_orders'] ?? true) {
            $customer->orders()->whereIn('status', ['pending', 'processing'])
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Customer deactivated'
                ]);
        }

        // Invalidate API tokens
        if ($data['revoke_tokens'] ?? true) {
            $customer->tokens()->delete();
        }

        // Clear cache for customer
        if (function_exists('cache')) {
            cache()->forget("customer_{$customer->id}_data");
            cache()->forget("customer_{$customer->id}_permissions");
        }
    }

    /**
     * Send deactivation notification
     *
     * @param Customer $customer
     * @param array $data
     */
    private function sendNotification(Customer $customer, array $data): void
    {
        try {
            Notification::send($customer, new CustomerDeactivatedNotification(
                $customer,
                $data['reason'] ?? 'No reason provided',
                $data['can_reactivate'] ?? true,
                $data['auto_reactivate_at'] ?? null
            ));

            // Send notification to internal team if specified
            if ($data['notify_team'] ?? true) {
                $this->notifyTeam($customer, $data);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send deactivation notification', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notify internal team about deactivation
     *
     * @param Customer $customer
     * @param array $data
     */
    private function notifyTeam(Customer $customer, array $data): void
    {
        // Example: Send to Slack or internal notification system
        if (config('services.slack.enabled')) {
            // Implement Slack notification
            // Slack::send("Customer {$customer->name} has been deactivated");
        }

        // Log for team visibility
        Log::channel('team')->info('Customer deactivated', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'reason' => $data['reason'] ?? 'No reason provided',
            'deactivated_by' => auth()->id()
        ]);
    }
}