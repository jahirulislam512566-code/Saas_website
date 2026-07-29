<?php

namespace App\Actions\Project;

use App\Models\AgencyProject;
use App\Models\User;
use App\Notifications\DeveloperAssignedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AssignDeveloperAction
{
    /**
     * Assign developer to project
     *
     * @param AgencyProject $project
     * @param array $data
     * @return AgencyProject
     * @throws \Exception
     */
    public function execute(AgencyProject $project, array $data): AgencyProject
    {
        return DB::transaction(function () use ($project, $data) {
            $developer = User::findOrFail($data['developer_id']);
            
            // Check if developer has required skills
            if (isset($data['check_skills']) && $data['check_skills']) {
                $this->checkDeveloperSkills($developer, $project);
            }

            // Check if developer is already assigned
            if ($project->teamMembers()->where('user_id', $developer->id)->exists()) {
                throw new \Exception('Developer already assigned to this project.');
            }

            // Assign developer
            $project->teamMembers()->attach($developer->id, [
                'role' => $data['role'] ?? 'developer',
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
            ]);

            // Update project status if needed
            if ($project->status === 'planning') {
                $project->update([
                    'status' => 'in_progress',
                    'updated_by' => auth()->id(),
                ]);
            }

            // Send notification
            if ($data['notify_developer'] ?? true) {
                Notification::send($developer, new DeveloperAssignedNotification($project));
            }

            Log::info('Developer assigned to project', [
                'project_id' => $project->id,
                'project_code' => $project->project_code,
                'developer_id' => $developer->id,
                'developer_email' => $developer->email,
                'assigned_by' => auth()->id()
            ]);

            return $project->fresh();
        });
    }

    private function checkDeveloperSkills(User $developer, AgencyProject $project): void
    {
        $requiredSkills = $project->required_skills ?? [];
        $developerSkills = $developer->skills ?? [];

        $missingSkills = array_diff($requiredSkills, $developerSkills);
        
        if (!empty($missingSkills)) {
            throw new \Exception('Developer missing required skills: ' . implode(', ', $missingSkills));
        }
    }
}