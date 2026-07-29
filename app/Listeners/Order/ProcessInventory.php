<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreated;
use App\Listeners\BaseListener;

class ProcessInventory extends BaseListener
{
    public function handle(OrderCreated $event): void
    {
        $this->logStart('OrderCreated', ['order_id' => $event->order->id]);

        try {
            foreach ($event->order->items as $item) {
                $product = $item->product;
                
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                    
                    // Log inventory change
                    \App\Models\InventoryLog::create([
                        'product_id' => $product->id,
                        'order_id' => $event->order->id,
                        'quantity' => -$item->quantity,
                        'type' => 'order',
                        'notes' => "Order #{$event->order->order_number}",
                    ]);
                }
            }

            $this->logComplete('OrderCreated', ['order_id' => $event->order->id]);
        } catch (\Exception $e) {
            $this->logError('OrderCreated', $e, ['order_id' => $event->order->id]);
        }
    }
}