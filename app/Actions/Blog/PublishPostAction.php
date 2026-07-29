<?php

namespace App\Actions\Blog;

use App\Models\BlogPost;
use App\Notifications\PostPublishedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PublishPostAction
{
    /**
     * Publish a blog post
     *
     * @param BlogPost $post
     * @param array $data
     * @return BlogPost
     * @throws \Exception
     */
    public function execute(BlogPost $post, array $data = []): BlogPost
    {
        return DB::transaction(function () use ($post, $data) {
            // Validate post can be published
            if ($post->status === 'published') {
                throw new \Exception('Post is already published.');
            }

            $oldStatus = $post->status;

            // Update post
            $post->update([
                'status' => 'published',
                'published_at' => $data['published_at'] ?? now(),
                'updated_by' => auth()->id(),
            ]);

            // Send notification to subscribers
            if ($data['notify_subscribers'] ?? true) {
                // Dispatch notification job
                // NotifySubscribersJob::dispatch($post);
            }

            Log::info('Blog post published', [
                'post_id' => $post->id,
                'title' => $post->title,
                'old_status' => $oldStatus,
                'new_status' => 'published',
                'published_by' => auth()->id()
            ]);

            return $post->fresh();
        });
    }
}