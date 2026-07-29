<?php

namespace App\Actions\Project;

use App\Models\Project;
use App\Notifications\ProjectArchivedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ArchiveProjectAction
{
    /**
     * Archive a project
     *
     * @param Project $project
     * @param array $data
     * @return Project
     */
    public function execute(Project $project, array $data = []): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $oldStatus = $project->status;

            // Only allow archiving completed or cancelled projects
            if (!in_array($oldStatus, ['completed', 'cancelled'])) {
                throw new \Exception('Only completed or cancelled projects can be archived.');
            }

            $project->update([
                'status' => 'archived',
                'archived_at' => now(),
                'archive_reason' => $data['reason'] ?? null,
                'archive_notes' => $data['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // Notify team members
            if ($data['notify_team'] ?? true) {
                $teamMembers = $project->teamMembers;
                Notification::send($teamMembers, new ProjectArchivedNotification(
                    $project,
                    $data['reason'] ?? 'Project archived'
                ));
            }

            Log::info('Project archived', [
                'project_id' => $project->id,
                'project_code' => $project->project_code,
                'old_status' => $oldStatus,
                'new_status' => 'archived',
                'archived_by' => auth()->id()
            ]);

            return $project->fresh();
        });
    }
}