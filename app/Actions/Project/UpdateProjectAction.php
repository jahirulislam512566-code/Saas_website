<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateProjectAction
{
    /**
     * Update an existing project
     *
     * @param Project $project
     * @param array $data
     * @return Project
     */
    public function execute(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $oldData = $project->toArray();

            // Validate status transition if status is being changed
            if (isset($data['status']) && $data['status'] !== $project->status) {
                $this->validateStatusTransition($project->status, $data['status']);
            }

            $project->update([
                'name' => $data['name'] ?? $project->name,
                'description' => $data['description'] ?? $project->description,
                'customer_id' => $data['customer_id'] ?? $project->customer_id,
                'start_date' => $data['start_date'] ?? $project->start_date,
                'end_date' => $data['end_date'] ?? $project->end_date,
                'budget' => $data['budget'] ?? $project->budget,
                'status' => $data['status'] ?? $project->status,
                'priority' => $data['priority'] ?? $project->priority,
                'assigned_to' => $data['assigned_to'] ?? $project->assigned_to,
                'updated_by' => auth()->id(),
            ]);

            // Update team members if provided
            if (isset($data['team_members'])) {
                $project->teamMembers()->sync($data['team_members']);
            }

            Log::info('Project updated', [
                'project_id' => $project->id,
                'project_code' => $project->project_code,
                'updated_by' => auth()->id(),
                'changes' => array_diff_assoc($project->toArray(), $oldData)
            ]);

            return $project->fresh();
        });
    }

    /**
     * Validate status transition
     *
     * @param string $currentStatus
     * @param string $newStatus
     * @throws \Exception
     */
    private function validateStatusTransition(string $currentStatus, string $newStatus): void
    {
        $allowedTransitions = [
            'draft' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'on_hold', 'cancelled'],
            'on_hold' => ['in_progress', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$currentStatus} to {$newStatus}");
        }
    }
}