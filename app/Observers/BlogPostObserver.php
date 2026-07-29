<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogPostObserver extends BaseObserver
{
    /**
     * Handle the BlogPost "creating" event.
     */
    public function creating(BlogPost $post): void
    {
        // Generate slug
        if (empty($post->slug)) {
            $post->slug = $this->generateSlug($post->title);
        }

        // Set default status
        if (empty($post->status)) {
            $post->status = 'draft';
        }

        // Set default author if not set
        if (empty($post->author_id)) {
            $post->author_id = auth()->id();
        }

        $this->log('creating', $post);
    }

    /**
     * Handle the BlogPost "created" event.
     */
    public function created(BlogPost $post): void
    {
        Cache::forget('blog_posts_count');
        Cache::forget('blog_posts_list');
        Cache::forget('blog_posts_featured');
        
        $this->createAuditLog($post, 'created');

        $this->log('created', $post);
    }

    /**
     * Handle the BlogPost "updating" event.
     */
    public function updating(BlogPost $post): void
    {
        // Track slug changes
        if ($post->isDirty('title')) {
            $post->slug = $this->generateSlug($post->title);
        }

        // Track status changes
        if ($post->isDirty('status')) {
            $oldStatus = $post->getOriginal('status');
            $newStatus = $post->status;
            
            $this->logStatusChange($post, $oldStatus, $newStatus);
        }

        $this->log('updating', $post);
    }

    /**
     * Handle the BlogPost "updated" event.
     */
    public function updated(BlogPost $post): void
    {
        Cache::forget("blog_post_{$post->id}");
        Cache::forget("blog_post_{$post->slug}");
        Cache::forget('blog_posts_list');
        Cache::forget('blog_posts_featured');
        
        $this->createAuditLog($post, 'updated', $post->getChanges());

        $this->log('updated', $post);
    }

    /**
     * Handle the BlogPost "deleting" event.
     */
    public function deleting(BlogPost $post): void
    {
        // Check if post can be deleted
        if ($post->status === 'published') {
            throw new \Exception('Cannot delete a published post. Please unpublish it first.');
        }

        $this->log('deleting', $post);
    }

    /**
     * Handle the BlogPost "deleted" event.
     */
    public function deleted(BlogPost $post): void
    {
        Cache::forget("blog_post_{$post->id}");
        Cache::forget('blog_posts_count');
        Cache::forget('blog_posts_list');

        $this->log('deleted', $post);
    }

    /**
     * Handle the BlogPost "restored" event.
     */
    public function restored(BlogPost $post): void
    {
        Cache::forget("blog_post_{$post->id}");
        Cache::forget('blog_posts_list');

        $this->log('restored', $post);
    }

    /**
     * Handle the BlogPost "forceDeleted" event.
     */
    public function forceDeleted(BlogPost $post): void
    {
        Cache::forget("blog_post_{$post->id}");
        Cache::forget('blog_posts_count');
        Cache::forget('blog_posts_list');

        Log::warning('Blog post permanently deleted', [
            'post_id' => $post->id,
            'title' => $post->title,
            'deleted_by' => $this->getUserId(),
        ]);
    }

    /**
     * Generate unique slug.
     */
    protected function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(BlogPost $post, string $oldStatus, string $newStatus): void
    {
        Log::info('Blog post status changed', [
            'post_id' => $post->id,
            'title' => $post->title,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(BlogPost $post, string $action, array $changes = []): void
    {
        // Create audit log entry if you have an AuditLog model
        // \App\Models\AuditLog::create([
        //     'model_type' => get_class($post),
        //     'model_id' => $post->id,
        //     'action' => $action,
        //     'changes' => $changes,
        //     'user_id' => $this->getUserId(),
        //     'ip_address' => $this->getIpAddress(),
        // ]);
    }
}