<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Template;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any templates.
     */
    public function viewAny(User $user): bool
    {
        // Anyone can view templates
        return true;
    }

    /**
     * Determine if the user can view the template.
     */
    public function view(User $user, Template $template): bool
    {
        // Super admin can view any template
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Anyone can view active templates
        if ($template->is_active) {
            return true;
        }

        // User can view inactive templates if they have permission
        return $this->hasPermission($user, 'view templates');
    }

    /**
     * Determine if the user can create templates.
     */
    public function create(User $user): bool
    {
        // Only admins and super admins can create templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'create templates');
    }

    /**
     * Determine if the user can update the template.
     */
    public function update(User $user, Template $template): bool
    {
        // Super admin can update any template
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Admin can update templates
        if ($this->isAdmin($user)) {
            return $this->hasPermission($user, 'update templates');
        }

        return false;
    }

    /**
     * Determine if the user can delete the template.
     */
    public function delete(User $user, Template $template): bool
    {
        // Super admin can delete any template
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Admin can delete templates
        if ($this->isAdmin($user)) {
            // Check if template is in use
            if ($template->usage_count > 0) {
                return false;
            }
            return $this->hasPermission($user, 'delete templates');
        }

        return false;
    }

    /**
     * Determine if the user can install the template.
     */
    public function install(User $user, Template $template): bool
    {
        // Super admin can install any template
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can install template on their own website
        if ($this->hasFeature($user, 'install_templates')) {
            return $this->hasPermission($user, 'install templates');
        }

        // Check if template is free
        if ($template->is_free) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can purchase the template.
     */
    public function purchase(User $user, Template $template): bool
    {
        // Can't purchase free templates
        if ($template->is_free) {
            return false;
        }

        // Super admin can purchase any template
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can purchase templates
        if ($this->hasFeature($user, 'purchase_templates')) {
            return $this->hasPermission($user, 'purchase templates');
        }

        return false;
    }

    /**
     * Determine if the user can activate the template.
     */
    public function activate(User $user, Template $template): bool
    {
        // Only admins and super admins can activate templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'activate templates');
    }

    /**
     * Determine if the user can deactivate the template.
     */
    public function deactivate(User $user, Template $template): bool
    {
        // Only admins and super admins can deactivate templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'deactivate templates');
    }

    /**
     * Determine if the user can preview the template.
     */
    public function preview(User $user, Template $template): bool
    {
        // Anyone can preview active templates
        if ($template->is_active) {
            return true;
        }

        // Super admin can preview any template
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Admin can preview templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'preview templates');
    }

    /**
     * Determine if the user can export the template.
     */
    public function export(User $user, Template $template): bool
    {
        // Only admins and super admins can export templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'export templates');
    }

    /**
     * Determine if the user can import templates.
     */
    public function import(User $user): bool
    {
        // Only admins and super admins can import templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'import templates');
    }

    /**
     * Determine if the user can duplicate the template.
     */
    public function duplicate(User $user, Template $template): bool
    {
        // Only admins and super admins can duplicate templates
        return $this->isAdmin($user) && $this->hasPermission($user, 'duplicate templates');
    }

    /**
     * Determine if the user can view template analytics.
     */
    public function viewAnalytics(User $user, Template $template): bool
    {
        // Only admins and super admins can view template analytics
        return $this->isAdmin($user) && $this->hasPermission($user, 'view template analytics');
    }

    /**
     * Determine if the user can restore the template.
     */
    public function restore(User $user, Template $template): bool
    {
        // Only super admin can restore templates
        return $this->isSuperAdmin($user);
    }

    /**
     * Determine if the user can force delete the template.
     */
    public function forceDelete(User $user, Template $template): bool
    {
        // Only super admin can force delete templates
        return $this->isSuperAdmin($user);
    }
}