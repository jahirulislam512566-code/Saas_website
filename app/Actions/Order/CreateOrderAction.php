<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateOrderAction
{
    /**
     * Create a new order
     *
     * @param array $data
     * @return Order
     */
    public function execute(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Calculate totals
            $subtotal = $this->calculateSubtotal($data['items']);
            $tax = $this->calculateTax($subtotal, $data['tax_rate'] ?? 0);
            $discount = $this->calculateDiscount($subtotal, $data['discount'] ?? 0);
            $shipping = $data['shipping_cost'] ?? 0;
            $total = $subtotal + $tax - $discount + $shipping;

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $data['customer_id'],
                'user_id' => auth()->id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_rate' => $data['tax_rate'] ?? 0,
                'tax_amount' => $tax,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? 'fixed',
                'shipping_cost' => $shipping,
                'total' => $total,
                'currency' => $data['currency'] ?? 'USD',
                'notes' => $data['notes'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'billing_address' => $data['billing_address'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
                'payment_status' => 'pending',
                'created_by' => auth()->id(),
            ]);

            // Create order items
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'] ?? $product->price,
                    'total' => ($item['quantity']) * ($item['unit_price'] ?? $product->price),
                    'options' => $item['options'] ?? null,
                ]);

                // Update inventory
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            Log::info('Order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
                'total' => $order->total,
                'created_by' => auth()->id()
            ]);

            return $order->load('items.product');
        });
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $lastOrder = Order::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    private function calculateSubtotal(array $items): float
    {
        return array_reduce($items, function ($carry, $item) {
            $price = $item['unit_price'] ?? 0;
            return $carry + ($item['quantity'] * $price);
        }, 0);
    }

    private function calculateTax(float $subtotal, float $taxRate): float
    {
        return ($subtotal * $taxRate) / 100;
    }

    private function calculateDiscount(float $subtotal, float $discount): float
    {
        return $discount; // Fixed amount or percentage handled in order
    }
}