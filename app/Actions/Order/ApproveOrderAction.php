<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Notifications\OrderApprovedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ApproveOrderAction
{
    /**
     * Approve an order
     *
     * @param Order $order
     * @param array $data
     * @return Order
     * @throws \Exception
     */
    public function execute(Order $order, array $data = []): Order
    {
        return DB::transaction(function () use ($order, $data) {
            // Validate order can be approved
            if ($order->status !== 'pending') {
                throw new \Exception('Only pending orders can be approved.');
            }

            // Validate payment
            if ($order->payment_status !== 'paid') {
                throw new \Exception('Order must be paid before approval.');
            }

            $oldStatus = $order->status;

            $order->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'approval_notes' => $data['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // Send notification
            if ($data['notify_customer'] ?? true) {
                Notification::send($order->customer, new OrderApprovedNotification($order));
            }

            Log::info('Order approved', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => 'approved',
                'approved_by' => auth()->id()
            ]);

            return $order->fresh();
        });
    }
}