<?php

namespace App\Notifications\Project;

use App\Models\Project;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectAssignedNotification extends BaseNotification
{
    public Project $project;
    public string $role;

    public function __construct(Project $project, string $role)
    {
        $this->project = $project;
        $this->role = $role;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Project Assigned: {$this->project->name}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned to the project: ' . $this->project->name)
            ->line('**Project Type:** ' . ucfirst(str_replace('_', ' ', $this->project->project_type)))
            ->line('**Your Role:** ' . ucfirst($this->role))
            ->line('**Project Status:** ' . ucfirst($this->project->status))
            ->line('**Start Date:** ' . $this->project->start_date->format('M d, Y'))
            ->action('View Project', route('customer.projects.show', $this->project))
            ->line('Please review the project details and begin working on assigned tasks.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => "Project Assigned: {$this->project->name}",
            'message' => 'You have been assigned to the project: ' . $this->project->name,
            'type' => 'project_assigned',
            'icon' => 'fa-project-diagram',
            'color' => 'info',
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'role' => $this->role,
            'status' => $this->project->status,
            'action_url' => route('customer.projects.show', $this->project),
            'action_text' => 'View Project',
        ];
    }
}