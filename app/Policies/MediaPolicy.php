<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Media;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any media.
     */
    public function viewAny(User $user, Website $website): bool
    {
        // Super admin can view any media
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view media of their own website
        if ($this->isOwner($user, $website)) {
            return true;
        }

        return $this->hasPermission($user, 'view media');
    }

    /**
     * Determine if the user can view the media.
     */
    public function view(User $user, Media $media): bool
    {
        // Super admin can view any media
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view media of their own website
        if ($this->isOwner($user, $media->website)) {
            return true;
        }

        // Check if media is public
        if ($media->website->is_public) {
            return true;
        }

        return $this->hasPermission($user, 'view media');
    }

    /**
     * Determine if the user can create media.
     */
    public function create(User $user, Website $website): bool
    {
        // Super admin can create media on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can create media on their own website
        if ($this->isOwner($user, $website)) {
            // Check storage limit
            $subscription = $user->subscriptions()->where('status', 'active')->first();
            $maxStorage = $subscription?->plan->storage_limit ?? 100 * 1024 * 1024;
            $currentStorage = $website->media()->sum('size');
            
            if ($currentStorage >= $maxStorage && !$this->isSuperAdmin($user)) {
                return false;
            }

            if (!$this->hasFeature($user, 'upload_media')) {
                return false;
            }

            return $this->hasPermission($user, 'create media');
        }

        return false;
    }

    /**
     * Determine if the user can update the media.
     */
    public function update(User $user, Media $media): bool
    {
        // Super admin can update any media
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can update media of their own website
        if ($this->isOwner($user, $media->website)) {
            return $this->hasPermission($user, 'update media');
        }

        return false;
    }

    /**
     * Determine if the user can delete the media.
     */
    public function delete(User $user, Media $media): bool
    {
        // Super admin can delete any media
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can delete media of their own website
        if ($this->isOwner($user, $media->website)) {
            return $this->hasPermission($user, 'delete media');
        }

        return false;
    }

    /**
     * Determine if the user can download the media.
     */
    public function download(User $user, Media $media): bool
    {
        return $this->view($user, $media);
    }

    /**
     * Determine if the user can replace the media.
     */
    public function replace(User $user, Media $media): bool
    {
        // Super admin can replace any media
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can replace media of their own website
        if ($this->isOwner($user, $media->website)) {
            return $this->hasPermission($user, 'replace media');
        }

        return false;
    }

    /**
     * Determine if the user can bulk upload media.
     */
    public function bulkUpload(User $user, Website $website): bool
    {
        // Super admin can bulk upload on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can bulk upload on their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'bulk_upload')) {
                return false;
            }
            return $this->hasPermission($user, 'bulk upload');
        }

        return false;
    }

    /**
     * Determine if the user can bulk delete media.
     */
    public function bulkDelete(User $user, Website $website): bool
    {
        // Super admin can bulk delete on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can bulk delete on their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'bulk delete');
        }

        return false;
    }

    /**
     * Determine if the user can organize media.
     */
    public function organize(User $user, Website $website): bool
    {
        // Super admin can organize media on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can organize media on their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'organize media');
        }

        return false;
    }

    /**
     * Determine if the user can view media usage.
     */
    public function viewUsage(User $user, Website $website): bool
    {
        // Super admin can view media usage on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view media usage on their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'view media usage');
        }

        return false;
    }

    /**
     * Determine if the user can generate thumbnails.
     */
    public function generateThumbnails(User $user, Media $media): bool
    {
        // Super admin can generate thumbnails for any media
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can generate thumbnails for media of their own website
        if ($this->isOwner($user, $media->website)) {
            if (!$this->hasFeature($user, 'generate_thumbnails')) {
                return false;
            }
            return $this->hasPermission($user, 'generate thumbnails');
        }

        return false;
    }

    /**
     * Determine if the user can restore the media.
     */
    public function restore(User $user, Media $media): bool
    {
        // Only super admin can restore media
        return $this->isSuperAdmin($user);
    }

    /**
     * Determine if the user can force delete the media.
     */
    public function forceDelete(User $user, Media $media): bool
    {
        // Only super admin can force delete media
        return $this->isSuperAdmin($user);
    }
}