<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'website_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'is_featured',
        'allow_comments',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'settings',
        'published_at',
        'views',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string>
     */
    protected $appends = [
        'reading_time',
        'excerpt_short',
        'status_label',
        'status_color',
        'featured_image_url',
        'formatted_published_at',
        'published_at_human',
        'url',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the tenant that owns the post.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the website that owns the post.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get the author of the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the categories for the post.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post')->withTimestamps();
    }

    /**
     * Get the tags for the post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * Get all comments including replies.
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the approved comments.
     */
    public function approvedComments()
    {
        return $this->comments()->where('is_approved', true);
    }

    /**
     * Get the featured image media.
     */
    public function featuredImage()
    {
        return $this->belongsTo(Media::class, 'featured_image');
    }

    /**
     * Get the activities for the post.
     */
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include posts for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include draft posts.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include archived posts.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope a query to only include scheduled posts.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to search posts.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('content', 'LIKE', "%{$search}%")
              ->orWhere('excerpt', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('categories', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    /**
     * Scope a query to filter by tag.
     */
    public function scopeByTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    /**
     * Scope a query to get recent posts.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit);
    }

    /**
     * Scope a query to get popular posts (by comment count).
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->published()
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->limit($limit);
    }

    /**
     * Scope a query to get related posts.
     */
    public function scopeRelated($query, $post, $limit = 5)
    {
        $categoryIds = $post->categories->pluck('id')->toArray();
        
        return $query->published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->latest()
            ->limit($limit);
    }

    /**
     * Scope a query to order by latest.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get the reading time for the post.
     */
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        
        if ($minutes <= 1) {
            return '1 min read';
        }
        
        return $minutes . ' min read';
    }

    /**
     * Get the short excerpt.
     */
    public function getExcerptShortAttribute()
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        
        return substr(strip_tags($this->content), 0, 150) . '...';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            'scheduled' => 'Scheduled',
        ];
        
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'warning',
            'published' => 'success',
            'archived' => 'secondary',
            'scheduled' => 'info',
        ];
        
        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return null;
        }
        
        return asset('storage/' . $this->featured_image);
    }

    /**
     * Get the featured image thumbnail URL.
     */
    public function getFeaturedImageThumbnailAttribute()
    {
        if (!$this->featured_image) {
            return null;
        }
        
        // If using a thumbnail system
        $path = pathinfo($this->featured_image);
        return asset('storage/' . $path['dirname'] . '/thumb_' . $path['basename']);
    }

    /**
     * Get the formatted published date.
     */
    public function getFormattedPublishedAtAttribute()
    {
        if (!$this->published_at) {
            return null;
        }
        
        return $this->published_at->format('M d, Y');
    }

    /**
     * Get the published date in a human-readable format.
     */
    public function getPublishedAtHumanAttribute()
    {
        if (!$this->published_at) {
            return null;
        }
        
        return $this->published_at->diffForHumans();
    }

    /**
     * Get the URL for the post.
     */
    public function getUrlAttribute()
    {
        return route('blog.show', ['slug' => $this->slug]);
    }

    // ==================== MUTATORS ====================

    /**
     * Set the slug attribute.
     */
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $value = Str::slug($this->title);
        }
        
        $slug = $value;
        $count = 1;
        
        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $value . '-' . $count++;
        }
        
        $this->attributes['slug'] = $slug;
    }

    /**
     * Set the status attribute.
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        
        // Auto-set published_at when status changes to published
        if ($value === 'published' && !$this->published_at) {
            $this->attributes['published_at'] = now();
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if the post is published.
     */
    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    /**
     * Check if the post is a draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the post is archived.
     */
    public function isArchived()
    {
        return $this->status === 'archived';
    }

    /**
     * Check if the post is scheduled.
     */
    public function isScheduled()
    {
        return $this->status === 'scheduled' && 
               $this->published_at && 
               $this->published_at > now();
    }

    /**
     * Check if the post is featured.
     */
    public function isFeatured()
    {
        return $this->is_featured;
    }

    /**
     * Get the comment count.
     */
    public function getCommentCount()
    {
        return $this->approvedComments()->count();
    }

    /**
     * Increment the view count.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get the next post.
     */
    public function getNextPost()
    {
        return static::published()
            ->where('published_at', '>', $this->published_at)
            ->orderBy('published_at')
            ->first();
    }

    /**
     * Get the previous post.
     */
    public function getPreviousPost()
    {
        return static::published()
            ->where('published_at', '<', $this->published_at)
            ->orderBy('published_at', 'desc')
            ->first();
    }

    /**
     * Get validation rules.
     */
    public static function getValidationRules($id = null)
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $id],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'exists:media,id'],
            'status' => ['required', 'in:draft,published,archived,scheduled'],
            'is_featured' => ['nullable', 'boolean'],
            'allow_comments' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Auto-generate slug if not provided
            if (!$post->slug) {
                $post->slug = Str::slug($post->title);
            }

            // Set default values
            if (!$post->allow_comments) {
                $post->allow_comments = true;
            }

            if (!$post->views) {
                $post->views = 0;
            }
        });

        static::created(function ($post) {
            // Log activity if ActivityLog model exists
            if (class_exists(\App\Models\ActivityLog::class)) {
                \App\Models\ActivityLog::create([
                    'tenant_id' => $post->tenant_id ?? null,
                    'user_id' => auth()->id(),
                    'action' => 'created',
                    'subject_type' => self::class,
                    'subject_id' => $post->id,
                    'subject_name' => $post->title,
                    'description' => "Created post: {$post->title}",
                ]);
            }
        });

        static::updated(function ($post) {
            // Log activity if ActivityLog model exists
            if (class_exists(\App\Models\ActivityLog::class)) {
                \App\Models\ActivityLog::create([
                    'tenant_id' => $post->tenant_id ?? null,
                    'user_id' => auth()->id(),
                    'action' => 'updated',
                    'subject_type' => self::class,
                    'subject_id' => $post->id,
                    'subject_name' => $post->title,
                    'description' => "Updated post: {$post->title}",
                ]);
            }
        });

        static::deleted(function ($post) {
            // Log activity if ActivityLog model exists
            if (class_exists(\App\Models\ActivityLog::class)) {
                \App\Models\ActivityLog::create([
                    'tenant_id' => $post->tenant_id ?? null,
                    'user_id' => auth()->id(),
                    'action' => 'deleted',
                    'subject_type' => self::class,
                    'subject_id' => $post->id,
                    'subject_name' => $post->title,
                    'description' => "Deleted post: {$post->title}",
                ]);
            }
        });
    }
}