<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteProjectAction
{
    /**
     * Delete a project (soft delete)
     *
     * @param Project $project
     * @param bool $forceDelete
     * @return bool
     */
    public function execute(Project $project, bool $forceDelete = false): bool
    {
        return DB::transaction(function () use ($project, $forceDelete) {
            // Check if project has related records
            if ($this->hasRelatedRecords($project) && !$forceDelete) {
                throw new \Exception('Cannot delete project with related records.');
            }

            if ($forceDelete) {
                // Detach all team members
                $project->teamMembers()->detach();
                
                $project->forceDelete();
                Log::warning('Project permanently deleted', [
                    'project_id' => $project->id,
                    'project_code' => $project->project_code,
                    'deleted_by' => auth()->id()
                ]);
            } else {
                $project->delete();
                Log::info('Project soft deleted', [
                    'project_id' => $project->id,
                    'project_code' => $project->project_code,
                    'deleted_by' => auth()->id()
                ]);
            }

            return true;
        });
    }

    /**
     * Check if project has related records
     *
     * @param Project $project
     * @return bool
     */
    private function hasRelatedRecords(Project $project): bool
    {
        return $project->tasks()->exists() ||
               $project->invoices()->exists() ||
               $project->timeLogs()->exists();
    }
}