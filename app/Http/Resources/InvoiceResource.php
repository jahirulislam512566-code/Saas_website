<?php

namespace App\Http\Resources;

use App\Enums\InvoiceStatus;
use InvoiceItemResource;

class InvoiceResource extends BaseResource
{
    public function toArray($request): array
    {
        $status = $this->status ? InvoiceStatus::fromValue($this->status) : null;

        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'items' => InvoiceItemResource::collection($this->whenLoaded('items')),
            
            'status' => $status ? [
                'value' => $status->value,
                'label' => $status->label(),
                'description' => $status->description(),
                'color' => $status->color(),
                'icon' => $status->icon(),
                'needs_action' => $status->needsAction(),
                'is_paid' => $status->isPaid(),
                'is_final' => $status->isFinal(),
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
                'total' => $this->total,
                'total_formatted' => $this->formatMoney($this->total),
                'currency' => $this->currency,
            ],

            'dates' => [
                'issue_date' => $this->formatDate($this->issue_date, 'M d, Y'),
                'due_date' => $this->formatDate($this->due_date, 'M d, Y'),
                'paid_at' => $this->formatDate($this->paid_at, 'M d, Y'),
                'sent_at' => $this->formatDate($this->sent_at, 'M d, Y'),
                'viewed_at' => $this->formatDate($this->viewed_at, 'M d, Y'),
            ],

            'notes' => $this->notes,
            'terms' => $this->terms,
            
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            
            'is_overdue' => $this->is_overdue,
            'days_overdue' => $this->days_overdue,

            'links' => [
                'self' => route('api.invoices.show', $this->id),
                'download' => route('api.invoices.download', $this->id),
                'payment' => route('api.invoices.pay', $this->id),
                'customer' => route('api.customers.show', $this->customer_id),
            ],

            'created_at' => $this->formatDate($this->created_at, 'M d, Y'),
            'updated_at' => $this->formatDate($this->updated_at, 'M d, Y'),
        ];
    }

    public function with($request): array
    {
        return array_merge(parent::with($request), [
            'resource' => 'invoice',
        ]);
    }
}