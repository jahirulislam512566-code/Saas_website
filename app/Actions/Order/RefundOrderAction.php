<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Notifications\OrderRefundedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RefundOrderAction
{
    /**
     * Refund an order
     *
     * @param Order $order
     * @param array $data
     * @return Order
     * @throws \Exception
     */
    public function execute(Order $order, array $data = []): Order
    {
        return DB::transaction(function () use ($order, $data) {
            // Validate order can be refunded
            if (!in_array($order->status, ['approved', 'completed'])) {
                throw new \Exception('Order cannot be refunded in its current status: ' . $order->status);
            }

            if ($order->payment_status !== 'paid') {
                throw new \Exception('Order has not been paid.');
            }

            if ($order->refunded_at) {
                throw new \Exception('Order has already been refunded.');
            }

            $oldStatus = $order->status;

            // Calculate refund amount
            $refundAmount = $data['amount'] ?? $order->total;
            $refundReason = $data['reason'] ?? 'Customer requested refund';

            // Process refund via payment gateway
            if ($order->payment_method) {
                // Call payment gateway refund API
                // $this->processPaymentRefund($order, $refundAmount);
            }

            // Restore inventory
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Update order
            $order->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_reason' => $refundReason,
                'refund_notes' => $data['notes'] ?? null,
                'refunded_by' => auth()->id(),
                'payment_status' => 'refunded',
                'updated_by' => auth()->id(),
            ]);

            // Send notification
            if ($data['notify_customer'] ?? true) {
                Notification::send($order->customer, new OrderRefundedNotification($order));
            }

            Log::warning('Order refunded', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => 'refunded',
                'refund_amount' => $refundAmount,
                'reason' => $refundReason,
                'refunded_by' => auth()->id()
            ]);

            return $order->fresh();
        });
    }
}