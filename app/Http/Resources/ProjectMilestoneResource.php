<?php

namespace App\Http\Resources;

class ProjectMilestoneResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => [
                'value' => $this->status,
                'label' => ucfirst($this->status ?? 'pending'),
                'is_completed' => $this->status === 'completed',
            ],
            'due_date' => $this->formatDate($this->due_date, 'M d, Y'),
            'completed_at' => $this->formatDate($this->completed_at, 'M d, Y'),
            'created_at' => $this->formatDate($this->created_at, 'M d, Y'),
            'updated_at' => $this->formatDate($this->updated_at, 'M d, Y'),
        ];
    }
}