<?php

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelOrderAction
{
    /**
     * Cancel an order
     *
     * @param Order $order
     * @param array $data
     * @return Order
     * @throws \Exception
     */
    public function execute(Order $order, array $data = []): Order
    {
        return DB::transaction(function () use ($order, $data) {
            // Validate order can be cancelled
            if (!in_array($order->status, ['pending', 'processing', 'confirmed'])) {
                throw new \Exception('Order cannot be cancelled in its current status: ' . $order->status);
            }

            $oldStatus = $order->status;

            // Restore inventory
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Update order
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $data['reason'] ?? 'Customer requested cancellation',
                'cancellation_notes' => $data['notes'] ?? null,
                'cancelled_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Handle payment if already paid
            if ($order->payment_status === 'paid') {
                // Trigger refund process
                if ($data['refund'] ?? true) {
                    // Dispatch refund job or call refund action
                    // RefundPaymentAction::dispatch($order);
                }
            }

            Log::warning('Order cancelled', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'reason' => $data['reason'] ?? 'Customer requested cancellation',
                'cancelled_by' => auth()->id()
            ]);

            return $order->fresh();
        });
    }
}