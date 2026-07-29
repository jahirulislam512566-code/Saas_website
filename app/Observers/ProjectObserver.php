<?php

namespace App\Observers;

use App\Models\Project;
use App\Enums\ProjectStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProjectObserver extends BaseObserver
{
    /**
     * Handle the Project "creating" event.
     */
    public function creating(Project $project): void
    {
        // Generate project code
        if (empty($project->project_code)) {
            $project->project_code = $this->generateProjectCode();
        }

        // Set default status
        if (empty($project->status)) {
            $project->status = ProjectStatus::PLANNING->value;
        }

        // Set default priority
        if (empty($project->priority)) {
            $project->priority = 'medium';
        }

        $this->log('creating', $project);
    }

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        // Clear cache
        Cache::forget('projects_count');
        Cache::forget('projects_list');

        // Create default milestones
        $this->createDefaultMilestones($project);

        // Create audit log
        $this->createAuditLog($project, 'created');

        $this->log('created', $project);
    }

    /**
     * Handle the Project "updating" event.
     */
    public function updating(Project $project): void
    {
        // Track status changes
        if ($project->isDirty('status')) {
            $oldStatus = $project->getOriginal('status');
            $newStatus = $project->status;
            
            $this->validateStatusTransition($oldStatus, $newStatus);
            $this->logStatusChange($project, $oldStatus, $newStatus);
        }

        $this->log('updating', $project);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        // Clear cache
        Cache::forget("project_{$project->id}");
        Cache::forget("project_{$project->project_code}");
        Cache::forget('projects_list');

        // Create audit log
        $this->createAuditLog($project, 'updated', $project->getChanges());

        $this->log('updated', $project);
    }

    /**
     * Handle the Project "deleting" event.
     */
    public function deleting(Project $project): void
    {
        // Check if project can be deleted
        if ($project->status === ProjectStatus::IN_PROGRESS->value) {
            throw new \Exception('Cannot delete a project that is in progress.');
        }

        $this->log('deleting', $project);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        // Clear cache
        Cache::forget("project_{$project->id}");
        Cache::forget('projects_count');
        Cache::forget('projects_list');

        $this->log('deleted', $project);
    }

    /**
     * Generate unique project code.
     */
    protected function generateProjectCode(): string
    {
        $prefix = 'PRJ';
        $year = date('Y');
        $lastProject = Project::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastProject ? intval(substr($lastProject->project_code, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $year, $number);
    }

    /**
     * Create default milestones for project.
     */
    protected function createDefaultMilestones(Project $project): void
    {
        $milestones = [
            [
                'title' => 'Project Kickoff',
                'description' => 'Initial project meeting and planning',
                'order' => 1,
                'status' => 'pending',
            ],
            [
                'title' => 'Requirements Gathering',
                'description' => 'Collect and document project requirements',
                'order' => 2,
                'status' => 'pending',
            ],
            [
                'title' => 'Design Phase',
                'description' => 'Create designs and mockups',
                'order' => 3,
                'status' => 'pending',
            ],
            [
                'title' => 'Development Phase',
                'description' => 'Develop and build the project',
                'order' => 4,
                'status' => 'pending',
            ],
            [
                'title' => 'Testing & QA',
                'description' => 'Test and quality assurance',
                'order' => 5,
                'status' => 'pending',
            ],
            [
                'title' => 'Deployment',
                'description' => 'Deploy to production',
                'order' => 6,
                'status' => 'pending',
            ],
        ];

        foreach ($milestones as $milestone) {
            $project->milestones()->create($milestone);
        }
    }

    /**
     * Validate status transition.
     */
    protected function validateStatusTransition(string $oldStatus, string $newStatus): void
    {
        $allowedTransitions = [
            'planning' => ['in_progress', 'cancelled'],
            'in_progress' => ['review', 'on_hold', 'cancelled'],
            'review' => ['in_progress', 'completed', 'cancelled'],
            'on_hold' => ['in_progress', 'cancelled'],
            'completed' => ['archived'],
            'archived' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$oldStatus} to {$newStatus}");
        }
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(Project $project, string $oldStatus, string $newStatus): void
    {
        Log::info('Project status changed', [
            'project_id' => $project->id,
            'project_code' => $project->project_code,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Project $project, string $action, array $changes = []): void
    {
        // Create audit log entry
        // \App\Models\AuditLog::create([...]);
    }
}