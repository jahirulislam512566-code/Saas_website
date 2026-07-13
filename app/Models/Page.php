<?php
// app/Models/Page.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'website_id',
        'parent_id',
        'template_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'is_home',
        'is_featured',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'full_url',
        'edit_url',
        'status_badge',
        'formatted_created_at',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeHome($query)
    {
        return $query->where('is_home', true);
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%")
              ->orWhere('content', 'LIKE', "%{$search}%")
              ->orWhere('excerpt', 'LIKE', "%{$search}%");
        });
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getFullUrlAttribute(): string
    {
        if ($this->is_home) {
            return '/';
        }
        return '/' . $this->slug;
    }

    public function getEditUrlAttribute(): string
    {
        return route('admin.pages.edit', $this);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'published' => 'bg-green-100 text-green-800',
            'draft' => 'bg-yellow-100 text-yellow-800',
            'archived' => 'bg-gray-100 text-gray-800',
        ];
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    public function isHome(): bool
    {
        return $this->is_home;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function publish(): self
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        return $this;
    }

    public function unpublish(): self
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
        return $this;
    }

    public function archive(): self
    {
        $this->update([
            'status' => 'archived',
        ]);
        return $this;
    }

    public function getAncestors(): array
    {
        $ancestors = [];
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors[] = $parent;
            $parent = $parent->parent;
        }
        
        return array_reverse($ancestors);
    }

    public function getBreadcrumb(): array
    {
        $breadcrumb = [];
        $page = $this;
        
        while ($page) {
            $breadcrumb[] = $page;
            $page = $page->parent;
        }
        
        return array_reverse($breadcrumb);
    }

    public function duplicate(): self
    {
        $newPage = $this->replicate();
        $newPage->title = $this->title . ' (Copy)';
        $newPage->slug = $this->slug . '-copy';
        $newPage->status = 'draft';
        $newPage->published_at = null;
        $newPage->created_by = auth()->id();
        $newPage->save();

        // Duplicate sections
        foreach ($this->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $newPage->id;
            $newSection->save();

            // Duplicate components
            foreach ($section->components as $component) {
                $newComponent = $component->replicate();
                $newComponent->section_id = $newSection->id;
                $newComponent->save();
            }
        }

        return $newPage;
    }
}