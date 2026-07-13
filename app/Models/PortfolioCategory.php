<?php
// app/Models/PortfolioCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortfolioCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'parent_id',
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
    ];

    /**
     * Get the portfolio items for this category.
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class)
            ->where('is_active', true)
            ->where('is_published', true)
            ->orderBy('sort_order');
    }

    /**
     * Get all portfolio items including unpublished (for admin).
     */
    public function allPortfolios()
    {
        return $this->hasMany(Portfolio::class)
            ->orderBy('sort_order');
    }

    /**
     * Get the featured portfolio items for this category.
     */
    public function featuredPortfolios()
    {
        return $this->hasMany(Portfolio::class)
            ->where('is_active', true)
            ->where('is_published', true)
            ->where('is_featured', true)
            ->orderBy('sort_order');
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(PortfolioCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(PortfolioCategory::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get all child categories recursively.
     */
    public function allChildren()
    {
        return $this->hasMany(PortfolioCategory::class, 'parent_id')
            ->orderBy('sort_order');
    }

    /**
     * Get the active portfolios count.
     */
    public function getActivePortfoliosCountAttribute()
    {
        return $this->portfolios()->count();
    }

    /**
     * Get the total portfolios count (including inactive).
     */
    public function getTotalPortfoliosCountAttribute()
    {
        return $this->allPortfolios()->count();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Auto-generate slug when updating
        static::updating(function ($category) {
            if (empty($category->slug) || $category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

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
     * Scope a query to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the URL for the category.
     */
    public function getUrlAttribute()
    {
        return route('website.portfolio.category', $this->slug);
    }

    /**
     * Get the full path of parent categories.
     */
    public function getPathAttribute()
    {
        $path = collect([$this]);
        $parent = $this->parent;
        
        while ($parent) {
            $path->prepend($parent);
            $parent = $parent->parent;
        }
        
        return $path;
    }

    /**
     * Get breadcrumb trail.
     */
    public function getBreadcrumbAttribute()
    {
        $breadcrumbs = [];
        foreach ($this->path as $category) {
            $breadcrumbs[] = [
                'name' => $category->name,
                'url' => $category->url,
            ];
        }
        return $breadcrumbs;
    }

    /**
     * Check if category has children.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Check if category has portfolio items.
     */
    public function hasPortfolios()
    {
        return $this->portfolios()->count() > 0;
    }

    /**
     * Get category tree for dropdowns.
     */
    public static function getTree()
    {
        $categories = self::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return $categories;
    }

    /**
     * Get flat list for select dropdowns.
     */
    public static function getSelectList()
    {
        $categories = self::orderBy('sort_order')->get();
        $list = [];

        foreach ($categories as $category) {
            $list[$category->id] = $category->name;
        }

        return $list;
    }

    /**
     * Get nested select list with indentation.
     */
    public static function getNestedSelectList($prefix = '')
    {
        $categories = self::whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $list = [];
        
        foreach ($categories as $category) {
            $list[$category->id] = $prefix . $category->name;
            
            // Get children recursively
            $children = self::where('parent_id', $category->id)
                ->orderBy('sort_order')
                ->get();
            
            foreach ($children as $child) {
                $list[$child->id] = $prefix . '-- ' . $child->name;
                
                // Get grandchildren
                $grandchildren = self::where('parent_id', $child->id)
                    ->orderBy('sort_order')
                    ->get();
                
                foreach ($grandchildren as $grandchild) {
                    $list[$grandchild->id] = $prefix . '---- ' . $grandchild->name;
                }
            }
        }

        return $list;
    }

    /**
     * Delete category and handle related data.
     */
    public function deleteWithRelations()
    {
        // Move portfolios to parent category or delete them
        if ($this->portfolios()->count() > 0) {
            if ($this->parent_id) {
                // Move portfolios to parent
                $this->portfolios()->update(['category_id' => $this->parent_id]);
            } else {
                // Soft delete or delete portfolios
                foreach ($this->portfolios as $portfolio) {
                    $portfolio->delete();
                }
            }
        }

        // Move children to parent
        if ($this->children()->count() > 0) {
            $this->children()->update(['parent_id' => $this->parent_id]);
        }

        return $this->delete();
    }

    /**
     * Get category statistics.
     */
    public function getStatistics()
    {
        return [
            'total_portfolios' => $this->allPortfolios()->count(),
            'active_portfolios' => $this->portfolios()->count(),
            'featured_portfolios' => $this->featuredPortfolios()->count(),
            'children_count' => $this->children()->count(),
            'parent_name' => $this->parent ? $this->parent->name : 'None',
        ];
    }

    /**
     * Get the latest portfolios in this category.
     */
    public function getLatestPortfolios($limit = 4)
    {
        return $this->portfolios()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get the most viewed portfolios in this category.
     */
    public function getPopularPortfolios($limit = 4)
    {
        return $this->portfolios()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search portfolios in this category.
     */
    public function searchPortfolios($query)
    {
        return $this->portfolios()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%");
            })
            ->get();
    }
}