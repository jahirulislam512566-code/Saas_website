<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WebsitePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any websites.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'view websites');
    }

    /**
     * Determine if the user can view the website.
     */
    public function view(User $user, Website $website): bool
    {
        // Super admin can view any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Admin can view any website
        if ($this->isAdmin($user)) {
            return true;
        }

        // User can view their own website
        if ($this->isOwner($user, $website)) {
            return true;
        }

        // User can view if they have specific permission
        if ($this->hasPermission($user, 'view any websites')) {
            return true;
        }

        // Check if website is public
        if ($website->is_public && $website->status === 'published') {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create websites.
     */
    public function create(User $user): bool
    {
        // Check permission
        if (!$this->hasPermission($user, 'create websites')) {
            return false;
        }

        // Check if user can create website based on their plan
        $subscription = $user->subscriptions()->where('status', 'active')->first();
        $maxWebsites = $subscription?->plan->max_websites ?? 1;
        $currentWebsites = $user->websites()->count();

        if ($currentWebsites >= $maxWebsites && !$this->isSuperAdmin($user)) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can update the website.
     */
    public function update(User $user, Website $website): bool
    {
        // Super admin can update any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can update their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'update websites');
        }

        // Check if user has permission to update any website
        if ($this->hasPermission($user, 'update any websites')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the website.
     */
    public function delete(User $user, Website $website): bool
    {
        // Super admin can delete any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can delete their own website
        if ($this->isOwner($user, $website)) {
            // Check if website can be deleted (e.g., not published)
            if ($website->status === 'published' && !$this->hasPermission($user, 'delete published websites')) {
                return false;
            }
            return $this->hasPermission($user, 'delete websites');
        }

        // Check if user has permission to delete any website
        if ($this->hasPermission($user, 'delete any websites')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can publish the website.
     */
    public function publish(User $user, Website $website): bool
    {
        // Super admin can publish any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can publish their own website
        if ($this->isOwner($user, $website)) {
            // Check if user has reached website limit
            if (!$this->hasFeature($user, 'publish_websites')) {
                return false;
            }
            return $this->hasPermission($user, 'publish websites');
        }

        return false;
    }

    /**
     * Determine if the user can clone the website.
     */
    public function clone(User $user, Website $website): bool
    {
        // Super admin can clone any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can clone their own website
        if ($this->isOwner($user, $website)) {
            // Check if user has feature access
            if (!$this->hasFeature($user, 'clone_websites')) {
                return false;
            }
            return $this->hasPermission($user, 'clone websites');
        }

        return false;
    }

    /**
     * Determine if the user can assign domain to website.
     */
    public function assignDomain(User $user, Website $website): bool
    {
        // Super admin can assign domain to any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can assign domain to their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'custom_domain')) {
                return false;
            }
            return $this->hasPermission($user, 'assign domains');
        }

        return false;
    }

    /**
     * Determine if the user can manage settings of the website.
     */
    public function manageSettings(User $user, Website $website): bool
    {
        // Super admin can manage settings of any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage settings of their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'manage website settings');
        }

        return false;
    }

    /**
     * Determine if the user can view analytics of the website.
     */
    public function viewAnalytics(User $user, Website $website): bool
    {
        // Super admin can view analytics of any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view analytics of their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'analytics')) {
                return false;
            }
            return $this->hasPermission($user, 'view analytics');
        }

        return false;
    }

    /**
     * Determine if the user can export the website.
     */
    public function export(User $user, Website $website): bool
    {
        // Super admin can export any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can export their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'export_website')) {
                return false;
            }
            return $this->hasPermission($user, 'export websites');
        }

        return false;
    }

    /**
     * Determine if the user can restore the website.
     */
    public function restore(User $user, Website $website): bool
    {
        // Only super admin can restore websites
        return $this->isSuperAdmin($user);
    }

    /**
     * Determine if the user can force delete the website.
     */
    public function forceDelete(User $user, Website $website): bool
    {
        // Only super admin can force delete websites
        return $this->isSuperAdmin($user);
    }

    /**
     * Determine if the user can view the website's media.
     */
    public function viewMedia(User $user, Website $website): bool
    {
        return $this->view($user, $website);
    }

    /**
     * Determine if the user can manage the website's media.
     */
    public function manageMedia(User $user, Website $website): bool
    {
        // Super admin can manage media of any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage media of their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'media_management')) {
                return false;
            }
            return $this->hasPermission($user, 'manage media');
        }

        return false;
    }

    /**
     * Determine if the user can view the website's SEO.
     */
    public function viewSEO(User $user, Website $website): bool
    {
        return $this->view($user, $website);
    }

    /**
     * Determine if the user can manage the website's SEO.
     */
    public function manageSEO(User $user, Website $website): bool
    {
        // Super admin can manage SEO of any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage SEO of their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'seo')) {
                return false;
            }
            return $this->hasPermission($user, 'manage seo');
        }

        return false;
    }

    /**
     * Determine if the user can view the website's templates.
     */
    public function viewTemplates(User $user, Website $website): bool
    {
        return $this->view($user, $website);
    }

    /**
     * Determine if the user can change the website's template.
     */
    public function changeTemplate(User $user, Website $website): bool
    {
        // Super admin can change template of any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can change template of their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'change_template')) {
                return false;
            }
            return $this->hasPermission($user, 'change template');
        }

        return false;
    }
}