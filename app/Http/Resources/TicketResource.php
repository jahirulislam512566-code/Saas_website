<?php

namespace App\Http\Resources;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use TicketReplyResource;

class TicketResource extends BaseResource
{
    public function toArray($request): array
    {
        $status = $this->status ? TicketStatus::fromValue($this->status) : null;
        $priority = $this->priority ? Priority::fromValue($this->priority) : null;

        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject' => $this->subject,
            'description' => $this->description,
            
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'replies' => TicketReplyResource::collection($this->whenLoaded('replies')),
            'category' => $this->category?->name,

            'status' => $status ? [
                'value' => $status->value,
                'label' => $status->label(),
                'description' => $status->description(),
                'color' => $status->color(),
                'icon' => $status->icon(),
                'is_active' => $status->isActive(),
                'is_resolved' => $status->isResolved(),
            ] : null,

            'priority' => $priority ? [
                'value' => $priority->value,
                'label' => $priority->label(),
                'description' => $priority->description(),
                'color' => $priority->color(),
                'icon' => $priority->icon(),
                'sla_hours' => $priority->slaHours(),
                'sla_resolution_hours' => $priority->slaResolutionHours(),
                'severity' => $priority->severity(),
            ] : null,

            'statistics' => [
                'reply_count' => $this->replies_count ?? 0,
                'last_reply_at' => $this->formatDate($this->last_reply_at, 'M d, Y H:i'),
                'response_time' => $this->response_time,
            ],

            'timestamps' => [
                'created_at' => $this->formatDate($this->created_at, 'M d, Y H:i'),
                'updated_at' => $this->formatDate($this->updated_at, 'M d, Y H:i'),
                'closed_at' => $this->formatDate($this->closed_at, 'M d, Y H:i'),
                'resolved_at' => $this->formatDate($this->resolved_at, 'M d, Y H:i'),
            ],

            'links' => [
                'self' => route('api.tickets.show', $this->id),
                'customer' => route('api.customers.show', $this->customer_id),
                'replies' => route('api.tickets.replies', $this->id),
            ],
        ];
    }

    public function with($request): array
    {
        return array_merge(parent::with($request), [
            'resource' => 'ticket',
        ]);
    }
}