<?php

namespace App\Http\Resources;

class OrderItemResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product?->name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'unit_price_formatted' => $this->formatMoney($this->unit_price),
            'total' => $this->total,
            'total_formatted' => $this->formatMoney($this->total),
            'options' => $this->options,
            'product' => new ProductResource($this->whenLoaded('product')),
            'created_at' => $this->formatDate($this->created_at, 'M d, Y'),
        ];
    }
}