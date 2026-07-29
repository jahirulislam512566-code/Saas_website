<?php

namespace App\Observers;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderObserver extends BaseObserver
{
    /**
     * Handle the Order "creating" event.
     */
    public function creating(Order $order): void
    {
        // Generate order number
        if (empty($order->order_number)) {
            $order->order_number = $this->generateOrderNumber();
        }

        // Set default status
        if (empty($order->status)) {
            $order->status = OrderStatus::PENDING->value;
        }

        // Set default payment status
        if (empty($order->payment_status)) {
            $order->payment_status = 'pending';
        }

        $this->log('creating', $order);
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        Cache::forget('orders_count');
        Cache::forget('orders_list');
        
        $this->createAuditLog($order, 'created');

        $this->log('created', $order);
    }

    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order): void
    {
        // Track status changes
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;
            
            $this->validateStatusTransition($oldStatus, $newStatus);
            $this->logStatusChange($order, $oldStatus, $newStatus);
        }

        // Track payment status changes
        if ($order->isDirty('payment_status')) {
            $oldPaymentStatus = $order->getOriginal('payment_status');
            $newPaymentStatus = $order->payment_status;
            
            $this->logPaymentStatusChange($order, $oldPaymentStatus, $newPaymentStatus);
        }

        $this->log('updating', $order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        Cache::forget("order_{$order->id}");
        Cache::forget("order_{$order->order_number}");
        Cache::forget('orders_list');
        
        $this->createAuditLog($order, 'updated', $order->getChanges());

        $this->log('updated', $order);
    }

    /**
     * Handle the Order "deleting" event.
     */
    public function deleting(Order $order): void
    {
        // Check if order can be deleted
        if (!in_array($order->status, ['draft', 'cancelled', 'refunded'])) {
            throw new \Exception('Cannot delete an order that is not draft, cancelled, or refunded.');
        }

        $this->log('deleting', $order);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        Cache::forget("order_{$order->id}");
        Cache::forget('orders_count');
        Cache::forget('orders_list');

        $this->log('deleted', $order);
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        Cache::forget("order_{$order->id}");
        Cache::forget('orders_list');

        $this->log('restored', $order);
    }

    /**
     * Handle the Order "forceDeleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        Cache::forget("order_{$order->id}");
        Cache::forget('orders_count');
        Cache::forget('orders_list');

        Log::warning('Order permanently deleted', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'deleted_by' => $this->getUserId(),
        ]);
    }

    /**
     * Generate unique order number.
     */
    protected function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $lastOrder = Order::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    /**
     * Validate status transition.
     */
    protected function validateStatusTransition(string $oldStatus, string $newStatus): void
    {
        $allowedTransitions = [
            'draft' => ['pending', 'cancelled'],
            'pending' => ['processing', 'cancelled'],
            'processing' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => ['completed'],
            'completed' => ['refunded'],
            'cancelled' => [],
            'refunded' => [],
            'on_hold' => ['pending', 'processing', 'cancelled'],
            'failed' => ['pending'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$oldStatus} to {$newStatus}");
        }
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(Order $order, string $oldStatus, string $newStatus): void
    {
        Log::info('Order status changed', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Log payment status change.
     */
    protected function logPaymentStatusChange(Order $order, string $oldStatus, string $newStatus): void
    {
        Log::info('Order payment status changed', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_payment_status' => $oldStatus,
            'new_payment_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Order $order, string $action, array $changes = []): void
    {
        // Create audit log entry if you have an AuditLog model
        // \App\Models\AuditLog::create([
        //     'model_type' => get_class($order),
        //     'model_id' => $order->id,
        //     'action' => $action,
        //     'changes' => $changes,
        //     'user_id' => $this->getUserId(),
        //     'ip_address' => $this->getIpAddress(),
        // ]);
    }
}