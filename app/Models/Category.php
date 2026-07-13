<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_path',
        'level',
        'post_count',
        'children_count',
        'icon_html',
        'color_hex',
        'formatted_created_at',
        'formatted_updated_at',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the tenant that owns the category.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the posts for this category.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }

    /**
     * Get the user who created the category.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the category.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured categories.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include parent categories.
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include children categories.
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope a query for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to search categories.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    /**
     * Get the full path of the category.
     */
    public function getFullPathAttribute(): string
    {
        $path = $this->name;
        $parent = $this->parent;
        
        while ($parent) {
            $path = $parent->name . ' / ' . $path;
            $parent = $parent->parent;
        }
        
        return $path;
    }

    /**
     * Get the level of the category (0 for root).
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        
        return $level;
    }

    /**
     * Get the post count for this category.
     */
    public function getPostCountAttribute(): int
    {
        return $this->posts()->where('status', 'published')->count();
    }

    /**
     * Get the children count for this category.
     */
    public function getChildrenCountAttribute(): int
    {
        return $this->children()->count();
    }

    /**
     * Get the icon HTML.
     */
    public function getIconHtmlAttribute(): string
    {
        if (!$this->icon) {
            return '<i class="fas fa-folder text-gray-400"></i>';
        }
        
        if (str_starts_with($this->icon, 'fa-')) {
            return '<i class="fas ' . $this->icon . '"></i>';
        }
        
        return '<i class="' . $this->icon . '"></i>';
    }

    /**
     * Get the color hex value.
     */
    public function getColorHexAttribute(): string
    {
        $colors = [
            'slate' => '#64748b',
            'gray' => '#6b7280',
            'zinc' => '#71717a',
            'neutral' => '#737373',
            'stone' => '#78716c',
            'red' => '#ef4444',
            'orange' => '#f97316',
            'amber' => '#f59e0b',
            'yellow' => '#eab308',
            'lime' => '#84cc16',
            'green' => '#22c55e',
            'emerald' => '#10b981',
            'teal' => '#14b8a6',
            'cyan' => '#06b6d4',
            'sky' => '#0ea5e9',
            'blue' => '#3b82f6',
            'indigo' => '#6366f1',
            'violet' => '#8b5cf6',
            'purple' => '#a855f7',
            'fuchsia' => '#d946ef',
            'pink' => '#ec4899',
            'rose' => '#f43f5e',
        ];
        
        return $colors[$this->color] ?? '#6b7280';
    }

    /**
     * Get formatted created at.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get formatted updated at.
     */
    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at->format('M d, Y');
    }

    /**
     * Mutator for slug.
     */
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $value = Str::slug($this->name);
        }
        $this->attributes['slug'] = Str::slug($value);
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    /**
     * Get all ancestors of the category.
     */
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

    /**
     * Get all descendants of the category.
     */
    public function getDescendants(): array
    {
        $descendants = [];
        $children = $this->children;
        
        foreach ($children as $child) {
            $descendants[] = $child;
            $descendants = array_merge($descendants, $child->getDescendants());
        }
        
        return $descendants;
    }

    /**
     * Check if category has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if category is a parent.
     */
    public function isParent(): bool
    {
        return $this->hasChildren();
    }

    /**
     * Get the category tree.
     */
    public static function getTree($parentId = null)
    {
        $categories = self::where('parent_id', $parentId)
            ->ordered()
            ->get();
        
        foreach ($categories as $category) {
            $category->children = self::getTree($category->id);
        }
        
        return $categories;
    }

    /**
     * Get the category breadcrumb.
     */
    public function getBreadcrumb(): array
    {
        $breadcrumb = [];
        $category = $this;
        
        while ($category) {
            $breadcrumb[] = $category;
            $category = $category->parent;
        }
        
        return array_reverse($breadcrumb);
    }

    /**
     * Get the category url.
     */
    public function getUrl(): string
    {
        return route('website.categories.show', $this->slug);
    }

    /**
     * Get the category admin url.
     */
    public function getAdminUrl(): string
    {
        return route('admin.categories.edit', $this);
    }

    /**
     * Duplicate the category.
     */
    public function duplicate(): self
    {
        $newCategory = $this->replicate();
        $newCategory->name = $this->name . ' (Copy)';
        $newCategory->slug = $this->slug . '-copy';
        $newCategory->save();
        
        return $newCategory;
    }
}