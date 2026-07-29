<?php

namespace App\Actions\Project;

use App\Models\AgencyProject;
use App\Models\ProjectMilestone;
use App\Notifications\ProjectCompletedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CompleteProjectAction
{
    /**
     * Complete a project
     *
     * @param AgencyProject $project
     * @param array $data
     * @return AgencyProject
     * @throws \Exception
     */
    public function execute(AgencyProject $project, array $data = []): AgencyProject
    {
        return DB::transaction(function () use ($project, $data) {
            // Validate project can be completed
            if (!in_array($project->status, ['in_progress', 'review'])) {
                throw new \Exception('Project cannot be completed in its current status: ' . $project->status);
            }

            // Check all milestones are completed
            if ($data['require_milestones'] ?? true) {
                $incompleteMilestones = $project->milestones()
                    ->where('status', '!=', 'completed')
                    ->count();

                if ($incompleteMilestones > 0) {
                    throw new \Exception('All milestones must be completed before project completion.');
                }
            }

            $oldStatus = $project->status;

            // Update project
            $project->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $data['notes'] ?? null,
                'completed_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Mark all milestones as completed
            if ($data['complete_milestones'] ?? true) {
                $project->milestones()->where('status', '!=', 'completed')
                    ->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
            }

            // Generate final report if needed
            if ($data['generate_report'] ?? false) {
                $this->generateCompletionReport($project);
            }

            // Send notifications
            if ($data['notify_team'] ?? true) {
                Notification::send(
                    $project->teamMembers,
                    new ProjectCompletedNotification($project)
                );
            }

            if ($data['notify_customer'] ?? true) {
                Notification::send(
                    $project->customer,
                    new ProjectCompletedNotification($project)
                );
            }

            Log::info('Project completed', [
                'project_id' => $project->id,
                'project_code' => $project->project_code,
                'old_status' => $oldStatus,
                'new_status' => 'completed',
                'completed_by' => auth()->id()
            ]);

            return $project->fresh();
        });
    }

    private function generateCompletionReport(AgencyProject $project): void
    {
        // Generate and store completion report
        // This could be a PDF or detailed summary
        $report = [
            'project_name' => $project->name,
            'project_code' => $project->project_code,
            'start_date' => $project->start_date,
            'completion_date' => now(),
            'total_days' => $project->start_date->diffInDays(now()),
            'milestones_completed' => $project->milestones()->where('status', 'completed')->count(),
            'total_milestones' => $project->milestones()->count(),
        ];

        $project->update([
            'completion_report' => $report,
        ]);
    }
}