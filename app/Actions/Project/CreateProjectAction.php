<?php

namespace App\Actions\Project;

use App\Models\AgencyProject;
use App\Models\ProjectMilestone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateProjectAction
{
    /**
     * Create a new agency project
     *
     * @param array $data
     * @return AgencyProject
     */
    public function execute(array $data): AgencyProject
    {
        return DB::transaction(function () use ($data) {
            $project = AgencyProject::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'project_type' => $data['project_type'], // website, logo, seo, mobile_app
                'customer_id' => $data['customer_id'],
                'project_code' => $this->generateProjectCode(),
                'start_date' => $data['start_date'] ?? now(),
                'end_date' => $data['end_date'] ?? null,
                'budget' => $data['budget'] ?? null,
                'status' => 'planning',
                'priority' => $data['priority'] ?? 'medium',
                'assigned_team' => $data['assigned_team'] ?? [],
                'created_by' => auth()->id(),
            ]);

            // Create milestones if provided
            if (isset($data['milestones']) && is_array($data['milestones'])) {
                foreach ($data['milestones'] as $milestone) {
                    ProjectMilestone::create([
                        'project_id' => $project->id,
                        'title' => $milestone['title'],
                        'description' => $milestone['description'] ?? null,
                        'due_date' => $milestone['due_date'] ?? null,
                        'status' => 'pending',
                    ]);
                }
            }

            // Attach team members
            if (isset($data['team_members']) && is_array($data['team_members'])) {
                $project->teamMembers()->attach($data['team_members']);
            }

            Log::info('Agency project created', [
                'project_id' => $project->id,
                'project_code' => $project->project_code,
                'project_type' => $project->project_type,
                'customer_id' => $project->customer_id,
                'created_by' => auth()->id()
            ]);

            return $project->load(['milestones', 'teamMembers']);
        });
    }

    private function generateProjectCode(): string
    {
        $prefix = 'AGP';
        $year = date('Y');
        $lastProject = AgencyProject::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastProject ? intval(substr($lastProject->project_code, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $year, $number);
    }
}