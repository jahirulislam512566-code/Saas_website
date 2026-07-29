<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderItemResource;
use PaymentResource;

class OrderResource extends BaseResource
{
    public function toArray($request): array
    {
        $status = $this->status ? OrderStatus::fromValue($this->status) : null;
        $paymentStatus = $this->payment_status ? PaymentStatus::fromValue($this->payment_status) : null;

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            
            'status' => $status ? [
                'value' => $status->value,
                'label' => $status->label(),
                'description' => $status->description(),
                'color' => $status->color(),
                'icon' => $status->icon(),
                'is_active' => $status->isActive(),
                'is_completed' => $status->isCompleted(),
                'is_cancellable' => $status->isCancellable(),
            ] : null,

            'payment_status' => $paymentStatus ? [
                'value' => $paymentStatus->value,
                'label' => $paymentStatus->label(),
                'description' => $paymentStatus->description(),
                'color' => $paymentStatus->color(),
                'icon' => $paymentStatus->icon(),
                'is_successful' => $paymentStatus->isSuccessful(),
                'is_refundable' => $paymentStatus->isRefundable(),
            ] : null,

            'amounts' => [
                'subtotal' => $this->subtotal,
                'subtotal_formatted' => $this->formatMoney($this->subtotal),
                'tax_rate' => $this->tax_rate,
                'tax_amount' => $this->tax_amount,
                'tax_amount_formatted' => $this->formatMoney($this->tax_amount),
                'discount' => $this->discount,
                'discount_formatted' => $this->formatMoney($this->discount),
                'discount_type' => $this->discount_type,
                'shipping_cost' => $this->shipping_cost,
                'shipping_cost_formatted' => $this->formatMoney($this->shipping_cost),
                'total' => $this->total,
                'total_formatted' => $this->formatMoney($this->total),
                'currency' => $this->currency,
            ],

            'addresses' => [
                'shipping' => $this->shipping_address,
                'billing' => $this->billing_address,
            ],

            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            
            'timestamps' => [
                'created_at' => $this->formatDateTime($this->created_at),
                'updated_at' => $this->formatDateTime($this->updated_at),
                'approved_at' => $this->formatDateTime($this->approved_at),
                'shipped_at' => $this->formatDateTime($this->shipped_at),
                'delivered_at' => $this->formatDateTime($this->delivered_at),
                'cancelled_at' => $this->formatDateTime($this->cancelled_at),
                'refunded_at' => $this->formatDateTime($this->refunded_at),
            ],

            'links' => [
                'self' => route('api.orders.show', $this->id),
                'invoice' => $this->invoice_id ? route('api.invoices.show', $this->invoice_id) : null,
                'payment' => $this->payment_id ? route('api.payments.show', $this->payment_id) : null,
            ],
        ];
    }

    public function with($request): array
    {
        return array_merge(parent::with($request), [
            'resource' => 'order',
        ]);
    }
}