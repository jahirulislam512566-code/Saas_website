<?php

namespace App\Http\Resources;

use App\Enums\CustomerStatus;

class CustomerResource extends BaseResource
{
    public function toArray($request): array
    {
        $status = $this->status ? CustomerStatus::fromValue($this->status) : null;

        return [
            'id' => $this->id,
            'customer_number' => $this->customer_number,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'website' => $this->website,
            'tax_id' => $this->tax_id,
            'industry' => $this->industry,
            'address' => [
                'street' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'full' => $this->full_address,
            ],
            'status' => $status ? [
                'value' => $status->value,
                'label' => $status->label(),
                'color' => $status->color(),
                'icon' => $status->icon(),
            ] : null,
            'statistics' => [
                'total_orders' => $this->orders_count ?? 0,
                'total_spent' => $this->total_spent ?? 0,
                'total_projects' => $this->projects_count ?? 0,
                'total_tickets' => $this->tickets_count ?? 0,
            ],
            'created_at' => $this->formatDate($this->created_at, 'M d, Y'),
            'updated_at' => $this->formatDate($this->updated_at, 'M d, Y'),
            'deactivated_at' => $this->formatDate($this->deactivated_at, 'M d, Y'),
            'links' => [
                'self' => route('api.customers.show', $this->id),
                'orders' => route('api.customers.orders', $this->id),
                'projects' => route('api.customers.projects', $this->id),
                'invoices' => route('api.customers.invoices', $this->id),
                'tickets' => route('api.customers.tickets', $this->id),
            ],
        ];
    }

    public function with($request): array
    {
        return array_merge(parent::with($request), [
            'resource' => 'customer',
        ]);
    }
}