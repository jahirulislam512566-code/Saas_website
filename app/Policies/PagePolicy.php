<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Page;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any pages.
     */
    public function viewAny(User $user, Website $website): bool
    {
        // Super admin can view any pages
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view pages of their own website
        if ($this->isOwner($user, $website)) {
            return true;
        }

        return $this->hasPermission($user, 'view pages');
    }

    /**
     * Determine if the user can view the page.
     */
    public function view(User $user, Page $page): bool
    {
        // Super admin can view any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view pages of their own website
        if ($this->isOwner($user, $page->website)) {
            return true;
        }

        // Check if page is published and public
        if ($page->status === 'published' && $page->website->is_public) {
            return true;
        }

        return $this->hasPermission($user, 'view pages');
    }

    /**
     * Determine if the user can create pages.
     */
    public function create(User $user, Website $website): bool
    {
        // Super admin can create pages on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can create pages on their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'create_pages')) {
                return false;
            }
            return $this->hasPermission($user, 'create pages');
        }

        return false;
    }

    /**
     * Determine if the user can update the page.
     */
    public function update(User $user, Page $page): bool
    {
        // Super admin can update any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can update pages of their own website
        if ($this->isOwner($user, $page->website)) {
            return $this->hasPermission($user, 'update pages');
        }

        return false;
    }

    /**
     * Determine if the user can delete the page.
     */
    public function delete(User $user, Page $page): bool
    {
        // Super admin can delete any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can delete pages of their own website
        if ($this->isOwner($user, $page->website)) {
            // Prevent deletion of home page
            if ($page->is_home) {
                return false;
            }
            return $this->hasPermission($user, 'delete pages');
        }

        return false;
    }

    /**
     * Determine if the user can publish the page.
     */
    public function publish(User $user, Page $page): bool
    {
        // Super admin can publish any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can publish pages of their own website
        if ($this->isOwner($user, $page->website)) {
            if (!$this->hasFeature($user, 'publish_pages')) {
                return false;
            }
            return $this->hasPermission($user, 'publish pages');
        }

        return false;
    }

    /**
     * Determine if the user can set page as home.
     */
    public function setHome(User $user, Page $page): bool
    {
        // Super admin can set any page as home
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can set home page on their own website
        if ($this->isOwner($user, $page->website)) {
            return $this->hasPermission($user, 'set home page');
        }

        return false;
    }

    /**
     * Determine if the user can duplicate the page.
     */
    public function duplicate(User $user, Page $page): bool
    {
        // Super admin can duplicate any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can duplicate pages of their own website
        if ($this->isOwner($user, $page->website)) {
            if (!$this->hasFeature($user, 'duplicate_pages')) {
                return false;
            }
            return $this->hasPermission($user, 'duplicate pages');
        }

        return false;
    }

    /**
     * Determine if the user can reorder pages.
     */
    public function reorder(User $user, Website $website): bool
    {
        // Super admin can reorder pages on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can reorder pages on their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'reorder pages');
        }

        return false;
    }

    /**
     * Determine if the user can manage page sections.
     */
    public function manageSections(User $user, Page $page): bool
    {
        // Super admin can manage sections on any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage sections on pages of their own website
        if ($this->isOwner($user, $page->website)) {
            if (!$this->hasFeature($user, 'manage_sections')) {
                return false;
            }
            return $this->hasPermission($user, 'manage sections');
        }

        return false;
    }

    /**
     * Determine if the user can view page SEO.
     */
    public function viewSEO(User $user, Page $page): bool
    {
        return $this->view($user, $page);
    }

    /**
     * Determine if the user can manage page SEO.
     */
    public function manageSEO(User $user, Page $page): bool
    {
        // Super admin can manage SEO on any page
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage SEO on pages of their own website
        if ($this->isOwner($user, $page->website)) {
            if (!$this->hasFeature($user, 'seo')) {
                return false;
            }
            return $this->hasPermission($user, 'manage seo');
        }

        return false;
    }

    /**
     * Determine if the user can restore the page.
     */
    public function restore(User $user, Page $page): bool
    {
        // Only super admin can restore pages
        return $this->isSuperAdmin($user);
    }

    /**
     * Determine if the user can force delete the page.
     */
    public function forceDelete(User $user, Page $page): bool
    {
        // Only super admin can force delete pages
        return $this->isSuperAdmin($user);
    }
}