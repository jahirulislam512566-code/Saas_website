<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Website;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository extends BaseRepository
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function findBySlug(Website $website, string $slug): ?Post
    {
        return $this->model->where('website_id', $website->id)
            ->where('slug', $slug)
            ->first();
    }

    public function findByWebsite(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function findByWebsitePaginated(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function findByAuthor(User $user): Collection
    {
        return $this->model->where('author_id', $user->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function findByCategory(Website $website, string $category): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('category', $category)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function findByTag(Website $website, string $tag): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->whereJsonContains('tags', $tag)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function createPost(Website $website, User $author, array $data): Post
    {
        $data['website_id'] = $website->id;
        $data['author_id'] = $author->id;
        $data['slug'] = $this->generateUniqueSlug($website, $data['title']);
        
        return $this->create($data);
    }

    public function updatePost(Post $post, array $data): Post
    {
        if (isset($data['title']) && $data['title'] !== $post->title) {
            $data['slug'] = $this->generateUniqueSlug($post->website, $data['title']);
        }
        
        return $this->update($post, $data);
    }

    public function publishPost(Post $post): Post
    {
        return $this->update($post, [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublishPost(Post $post): Post
    {
        return $this->update($post, [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function getFeaturedPosts(Website $website, int $limit = 5): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRecentPosts(Website $website, int $limit = 10): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPopularPosts(Website $website, int $limit = 10): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRelatedPosts(Post $post, int $limit = 5): Collection
    {
        $tags = $post->tags ?? [];
        $category = $post->category;
        
        $query = $this->model->where('website_id', $post->website_id)
            ->where('status', 'published')
            ->where('id', '!=', $post->id);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if (!empty($tags)) {
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }
        
        return $query->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function incrementViews(Post $post): Post
    {
        $post->increment('views');
        return $post->fresh();
    }

    public function searchPosts(Website $website, string $searchTerm): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('excerpt', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('category', 'LIKE', "%{$searchTerm}%");
            })
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function getCategories(Website $website): array
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->groupBy('category')
            ->selectRaw('category, count(*) as count')
            ->get()
            ->pluck('category')
            ->toArray();
    }

    public function getTags(Website $website): array
    {
        $tags = $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->pluck('tags')
            ->toArray();
        
        $allTags = [];
        foreach ($tags as $tagArray) {
            if (is_array($tagArray)) {
                $allTags = array_merge($allTags, $tagArray);
            }
        }
        
        return array_unique($allTags);
    }

    public function getArchives(Website $website): array
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->toArray();
    }

    public function getPostStatistics(Website $website): array
    {
        return [
            'total' => $this->model->where('website_id', $website->id)->count(),
            'published' => $this->model->where('website_id', $website->id)->where('status', 'published')->count(),
            'draft' => $this->model->where('website_id', $website->id)->where('status', 'draft')->count(),
            'total_views' => $this->model->where('website_id', $website->id)->sum('views'),
            'featured' => $this->model->where('website_id', $website->id)->where('is_featured', true)->count(),
            'categories' => $this->getCategories($website),
            'most_viewed' => $this->getPopularPosts($website, 1)->first(),
        ];
    }

    public function duplicatePost(Post $post): Post
    {
        $newPost = $post->replicate();
        $newPost->title = $post->title . ' (Copy)';
        $newPost->slug = $this->generateUniqueSlug($post->website, $newPost->title);
        $newPost->status = 'draft';
        $newPost->published_at = null;
        $newPost->views = 0;
        $newPost->is_featured = false;
        $newPost->save();
        
        return $newPost->fresh();
    }

    public function toggleFeatured(Post $post): Post
    {
        $post->is_featured = !$post->is_featured;
        $post->save();
        return $post->fresh();
    }

    public function updateSeo(Post $post, array $seoData): Post
    {
        $post->update([
            'meta_title' => $seoData['meta_title'] ?? null,
            'meta_description' => $seoData['meta_description'] ?? null,
            'meta_keywords' => $seoData['meta_keywords'] ?? null,
        ]);
        
        return $post->fresh();
    }

    private function generateUniqueSlug(Website $website, string $title): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $count = 2;
        
        while ($this->model->where('website_id', $website->id)->where('slug', $slug)->exists()) {
            $slug = \Illuminate\Support\Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
}