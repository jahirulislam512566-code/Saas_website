<?php

namespace App\Actions\Blog;

use App\Models\BlogPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeletePostAction
{
    /**
     * Delete a blog post
     *
     * @param BlogPost $post
     * @param bool $forceDelete
     * @return bool
     */
    public function execute(BlogPost $post, bool $forceDelete = false): bool
    {
        return DB::transaction(function () use ($post, $forceDelete) {
            if ($forceDelete) {
                // Detach categories
                $post->categories()->detach();
                
                // Delete associated media
                if ($post->featured_image) {
                    // Delete image from storage
                    // Storage::delete($post->featured_image);
                }

                $post->forceDelete();
                Log::warning('Blog post permanently deleted', [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'deleted_by' => auth()->id()
                ]);
            } else {
                $post->delete();
                Log::info('Blog post soft deleted', [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'deleted_by' => auth()->id()
                ]);
            }

            return true;
        });
    }
}