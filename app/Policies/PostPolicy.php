<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any posts.
     */
    public function viewAny(User $user, Website $website): bool
    {
        // Super admin can view any posts
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view posts of their own website
        if ($this->isOwner($user, $website)) {
            return true;
        }

        return $this->hasPermission($user, 'view posts');
    }

    /**
     * Determine if the user can view the post.
     */
    public function view(User $user, Post $post): bool
    {
        // Super admin can view any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view posts of their own website
        if ($this->isOwner($user, $post->website)) {
            return true;
        }

        // Check if post is published
        if ($post->status === 'published') {
            return true;
        }

        return $this->hasPermission($user, 'view posts');
    }

    /**
     * Determine if the user can create posts.
     */
    public function create(User $user, Website $website): bool
    {
        // Super admin can create posts on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can create posts on their own website
        if ($this->isOwner($user, $website)) {
            if (!$this->hasFeature($user, 'create_posts')) {
                return false;
            }
            return $this->hasPermission($user, 'create posts');
        }

        return false;
    }

    /**
     * Determine if the user can update the post.
     */
    public function update(User $user, Post $post): bool
    {
        // Super admin can update any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can update posts of their own website
        if ($this->isOwner($user, $post->website)) {
            return $this->hasPermission($user, 'update posts');
        }

        return false;
    }

    /**
     * Determine if the user can delete the post.
     */
    public function delete(User $user, Post $post): bool
    {
        // Super admin can delete any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can delete posts of their own website
        if ($this->isOwner($user, $post->website)) {
            return $this->hasPermission($user, 'delete posts');
        }

        return false;
    }

    /**
     * Determine if the user can publish the post.
     */
    public function publish(User $user, Post $post): bool
    {
        // Super admin can publish any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can publish posts of their own website
        if ($this->isOwner($user, $post->website)) {
            if (!$this->hasFeature($user, 'publish_posts')) {
                return false;
            }
            return $this->hasPermission($user, 'publish posts');
        }

        return false;
    }

    /**
     * Determine if the user can feature the post.
     */
    public function feature(User $user, Post $post): bool
    {
        // Super admin can feature any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can feature posts of their own website
        if ($this->isOwner($user, $post->website)) {
            if (!$this->hasFeature($user, 'feature_posts')) {
                return false;
            }
            return $this->hasPermission($user, 'feature posts');
        }

        return false;
    }

    /**
     * Determine if the user can view post analytics.
     */
    public function viewAnalytics(User $user, Post $post): bool
    {
        // Super admin can view analytics of any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can view analytics of posts of their own website
        if ($this->isOwner($user, $post->website)) {
            if (!$this->hasFeature($user, 'post_analytics')) {
                return false;
            }
            return $this->hasPermission($user, 'view post analytics');
        }

        return false;
    }

    /**
     * Determine if the user can duplicate the post.
     */
    public function duplicate(User $user, Post $post): bool
    {
        // Super admin can duplicate any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can duplicate posts of their own website
        if ($this->isOwner($user, $post->website)) {
            if (!$this->hasFeature($user, 'duplicate_posts')) {
                return false;
            }
            return $this->hasPermission($user, 'duplicate posts');
        }

        return false;
    }

    /**
     * Determine if the user can manage post categories.
     */
    public function manageCategories(User $user, Website $website): bool
    {
        // Super admin can manage categories on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage categories on their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'manage categories');
        }

        return false;
    }

    /**
     * Determine if the user can manage post tags.
     */
    public function manageTags(User $user, Website $website): bool
    {
        // Super admin can manage tags on any website
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage tags on their own website
        if ($this->isOwner($user, $website)) {
            return $this->hasPermission($user, 'manage tags');
        }

        return false;
    }

    /**
     * Determine if the user can view post SEO.
     */
    public function viewSEO(User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    /**
     * Determine if the user can manage post SEO.
     */
    public function manageSEO(User $user, Post $post): bool
    {
        // Super admin can manage SEO on any post
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // User can manage SEO on posts of their own website
        if ($this->isOwner($user, $post->website)) {
            if (!$this->hasFeature($user, 'seo')) {
                return false;
            }
            return $this->hasPermission($user, 'manage seo');
        }

        return false;
    }

    /**
     * Determine if the user can restore the post.
     */
    public function restore(User $user, Post $post): bool
    {
        // Only super admin can restore posts
        return $this->isSuperAdmin($user);
    }

    /**
     * Determine if the user can force delete the post.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        // Only super admin can force delete posts
        return $this->isSuperAdmin($user);
    }
}
