<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy extends BasePolicy
{
    /**
     * Determine if user can view any projects.
     */
    public function viewAny(User $user): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('view_projects')
            ? Response::allow()
            : Response::deny('You do not have permission to view projects.');
    }

    /**
     * Determine if user can view a specific project.
     */
    public function view(User $user, Project $project): Response
    {
        // Admin can view any project
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Customer can view their own projects
        if ($user->customer_id === $project->customer_id) {
            return Response::allow();
        }

        // Team members can view assigned projects
        if ($project->teamMembers()->where('user_id', $user->id)->exists()) {
            return Response::allow();
        }

        // Users with permission can view projects
        if ($user->hasPermission('view_projects')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this project.');
    }

    /**
     * Determine if user can create projects.
     */
    public function create(User $user): Response
    {
        return $this->isAdmin($user) || $user->hasPermission('create_projects')
            ? Response::allow()
            : Response::deny('You do not have permission to create projects.');
    }

    /**
     * Determine if user can update a project.
     */
    public function update(User $user, Project $project): Response
    {
        // Admin can update any project
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Project manager can update assigned projects
        if ($project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists()) {
            return Response::allow();
        }

        // Users with permission can update projects
        if ($user->hasPermission('edit_projects')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this project.');
    }

    /**
     * Determine if user can delete a project.
     */
    public function delete(User $user, Project $project): Response
    {
        // Admin can delete any project
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Users with permission can delete projects
        if ($user->hasPermission('delete_projects')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this project.');
    }

    /**
     * Determine if user can assign team members.
     */
    public function assignTeam(User $user, Project $project): Response
    {
        // Admin can assign any project
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Project manager can assign team members
        if ($project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists()) {
            return Response::allow();
        }

        // Users with permission can assign team members
        if ($user->hasPermission('assign_project_team')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to assign team members.');
    }

    /**
     * Determine if user can complete a project.
     */
    public function complete(User $user, Project $project): Response
    {
        // Admin can complete any project
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Project manager can complete assigned projects
        if ($project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists()) {
            return Response::allow();
        }

        // Users with permission can complete projects
        if ($user->hasPermission('complete_projects')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to complete this project.');
    }

    /**
     * Determine if user can archive a project.
     */
    public function archive(User $user, Project $project): Response
    {
        // Admin can archive any project
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        // Users with permission can archive projects
        if ($user->hasPermission('archive_projects')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to archive this project.');
    }
}