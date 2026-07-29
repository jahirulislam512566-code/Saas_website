<?php

namespace App\Observers;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PaymentObserver extends BaseObserver
{
    /**
     * Handle the Payment "creating" event.
     */
    public function creating(Payment $payment): void
    {
        // Set default status
        if (empty($payment->status)) {
            $payment->status = PaymentStatus::PENDING->value;
        }

        // Set default currency
        if (empty($payment->currency)) {
            $payment->currency = config('app.currency', 'USD');
        }

        $this->log('creating', $payment);
    }

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        Cache::forget('payments_count');
        Cache::forget('payments_list');
        
        $this->createAuditLog($payment, 'created');

        // Update order payment status
        if ($payment->order) {
            $payment->order->update([
                'payment_status' => $payment->status,
                'paid_at' => $payment->status === 'completed' ? now() : null,
            ]);
        }

        $this->log('created', $payment);
    }

    /**
     * Handle the Payment "updating" event.
     */
    public function updating(Payment $payment): void
    {
        // Track status changes
        if ($payment->isDirty('status')) {
            $oldStatus = $payment->getOriginal('status');
            $newStatus = $payment->status;
            
            $this->validateStatusTransition($oldStatus, $newStatus);
            $this->logStatusChange($payment, $oldStatus, $newStatus);
        }

        $this->log('updating', $payment);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        Cache::forget("payment_{$payment->id}");
        Cache::forget('payments_list');
        
        $this->createAuditLog($payment, 'updated', $payment->getChanges());

        // Update order payment status
        if ($payment->order) {
            $payment->order->update([
                'payment_status' => $payment->status,
                'paid_at' => $payment->status === 'completed' ? now() : null,
            ]);
        }

        $this->log('updated', $payment);
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        Cache::forget("payment_{$payment->id}");
        Cache::forget('payments_count');
        Cache::forget('payments_list');

        $this->log('deleted', $payment);
    }

    /**
     * Validate status transition.
     */
    protected function validateStatusTransition(string $oldStatus, string $newStatus): void
    {
        $allowedTransitions = [
            'pending' => ['processing', 'failed', 'cancelled'],
            'processing' => ['completed', 'failed'],
            'completed' => ['refunded', 'partially_refunded'],
            'failed' => ['pending'],
            'refunded' => [],
            'partially_refunded' => ['refunded'],
            'cancelled' => [],
            'authorized' => ['captured', 'cancelled'],
            'captured' => ['completed', 'refunded'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$oldStatus} to {$newStatus}");
        }
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(Payment $payment, string $oldStatus, string $newStatus): void
    {
        Log::info('Payment status changed', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Payment $payment, string $action, array $changes = []): void
    {
        // Create audit log entry if you have an AuditLog model
        // \App\Models\AuditLog::create([
        //     'model_type' => get_class($payment),
        //     'model_id' => $payment->id,
        //     'action' => $action,
        //     'changes' => $changes,
        //     'user_id' => $this->getUserId(),
        //     'ip_address' => $this->getIpAddress(),
        // ]);
    }
}