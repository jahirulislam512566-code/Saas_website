<?php

namespace App\Actions\Blog;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreatePostAction
{
    /**
     * Create a new blog post
     *
     * @param array $data
     * @return BlogPost
     */
    public function execute(array $data): BlogPost
    {
        return DB::transaction(function () use ($data) {
            $slug = Str::slug($data['title']);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (BlogPost::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $post = BlogPost::create([
                'title' => $data['title'],
                'slug' => $slug,
                'excerpt' => $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 200),
                'content' => $data['content'],
                'featured_image' => $data['featured_image'] ?? null,
                'author_id' => $data['author_id'] ?? auth()->id(),
                'status' => $data['status'] ?? 'draft', // draft, pending, published
                'published_at' => $data['status'] === 'published' ? now() : null,
                'meta_title' => $data['meta_title'] ?? $data['title'],
                'meta_description' => $data['meta_description'] ?? $data['excerpt'] ?? null,
                'tags' => $data['tags'] ?? [],
                'views' => 0,
                'is_featured' => $data['is_featured'] ?? false,
                'created_by' => auth()->id(),
            ]);

            // Attach categories
            if (isset($data['categories']) && is_array($data['categories'])) {
                $post->categories()->attach($data['categories']);
            }

            Log::info('Blog post created', [
                'post_id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'author_id' => $post->author_id,
                'created_by' => auth()->id()
            ]);

            return $post->load('categories');
        });
    }
}