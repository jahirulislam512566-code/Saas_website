<?php

namespace App\Http\Resources;

use App\Enums\ProjectStatus;
use App\Http\Resources\ProjectMilestoneResource;

class ProjectResource extends BaseResource
{
    public function toArray($request): array
    {
        $status = $this->status ? ProjectStatus::fromValue($this->status) : null;

        return [
            'id' => $this->id,
            'project_code' => $this->project_code,
            'name' => $this->name,
            'description' => $this->description,
            'project_type' => $this->project_type,
            'project_type_label' => $this->project_type ? ucwords(str_replace('_', ' ', $this->project_type)) : null,
            
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'team_members' => UserResource::collection($this->whenLoaded('teamMembers')),
            'milestones' => ProjectMilestoneResource::collection($this->whenLoaded('milestones')),
            
            'status' => $status ? [
                'value' => $status->value,
                'label' => $status->label(),
                'description' => $status->description(),
                'color' => $status->color(),
                'icon' => $status->icon(),
                'is_active' => $status->isActive(),
                'is_finished' => $status->isFinished(),
                'progress' => $status->progress(),
            ] : null,

            'dates' => [
                'start_date' => $this->formatDate($this->start_date, 'M d, Y'),
                'end_date' => $this->formatDate($this->end_date, 'M d, Y'),
                'completed_at' => $this->formatDate($this->completed_at, 'M d, Y'),
                'archived_at' => $this->formatDate($this->archived_at, 'M d, Y'),
            ],

            'budget' => $this->budget,
            'budget_formatted' => $this->formatMoney($this->budget),
            
            'priority' => [
                'value' => $this->priority,
                'label' => ucfirst($this->priority ?? 'medium'),
            ],
            
            'technologies' => $this->technologies ?? [],
            'requirements' => $this->requirements,
            'assigned_team' => $this->assigned_team ?? [],

            'statistics' => [
                'milestones' => [
                    'total' => $this->milestones_count ?? 0,
                    'completed' => $this->completed_milestones_count ?? 0,
                    'pending' => $this->pending_milestones_count ?? 0,
                ],
                'tasks' => [
                    'total' => $this->tasks_count ?? 0,
                    'completed' => $this->completed_tasks_count ?? 0,
                    'in_progress' => $this->in_progress_tasks_count ?? 0,
                ],
            ],

            'links' => [
                'self' => route('api.projects.show', $this->id),
                'customer' => route('api.customers.show', $this->customer_id),
                'tasks' => route('api.projects.tasks', $this->id),
                'milestones' => route('api.projects.milestones', $this->id),
            ],

            'created_at' => $this->formatDate($this->created_at, 'M d, Y'),
            'updated_at' => $this->formatDate($this->updated_at, 'M d, Y'),
        ];
    }

    public function with($request): array
    {
        return array_merge(parent::with($request), [
            'resource' => 'project',
        ]);
    }
}