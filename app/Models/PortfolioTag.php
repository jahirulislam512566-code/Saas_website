<?php
// app/Models/PortfolioTag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortfolioTag extends Model
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
        'color',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the portfolios for this tag.
     */
    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class, 'portfolio_tag')
            ->where('is_active', true)
            ->where('is_published', true)
            ->orderBy('sort_order');
    }

    /**
     * Get all portfolios including unpublished (for admin).
     */
    public function allPortfolios()
    {
        return $this->belongsToMany(Portfolio::class, 'portfolio_tag')
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
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        // Auto-generate slug when updating
        static::updating(function ($tag) {
            if (empty($tag->slug) || $tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Scope a query to only include active tags.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope a query to search tags.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * Get the URL for the tag.
     */
    public function getUrlAttribute()
    {
        return route('website.portfolio.tag', $this->slug);
    }

    /**
     * Get the badge color class.
     */
    public function getColorClassAttribute()
    {
        $colors = [
            'blue' => 'bg-blue-100 text-blue-800',
            'indigo' => 'bg-indigo-100 text-indigo-800',
            'purple' => 'bg-purple-100 text-purple-800',
            'pink' => 'bg-pink-100 text-pink-800',
            'red' => 'bg-red-100 text-red-800',
            'orange' => 'bg-orange-100 text-orange-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'green' => 'bg-green-100 text-green-800',
            'teal' => 'bg-teal-100 text-teal-800',
            'cyan' => 'bg-cyan-100 text-cyan-800',
            'gray' => 'bg-gray-100 text-gray-800',
        ];

        return $colors[$this->color ?? 'indigo'] ?? 'bg-indigo-100 text-indigo-800';
    }

    /**
     * Get the badge style attribute.
     */
    public function getBadgeStyleAttribute()
    {
        return "background-color: {$this->color_code}; color: white;";
    }

    /**
     * Get popular tags with portfolio count.
     */
    public static function getPopularTags($limit = 10)
    {
        return self::withCount('portfolios')
            ->where('is_active', true)
            ->having('portfolios_count', '>', 0)
            ->orderBy('portfolios_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get tags with portfolio count.
     */
    public static function getTagsWithCount()
    {
        return self::withCount('portfolios')
            ->where('is_active', true)
            ->having('portfolios_count', '>', 0)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get tag cloud with varying sizes based on popularity.
     */
    public static function getTagCloud($limit = 30)
    {
        $tags = self::withCount('portfolios')
            ->where('is_active', true)
            ->having('portfolios_count', '>', 0)
            ->orderBy('portfolios_count', 'desc')
            ->limit($limit)
            ->get();

        $maxCount = $tags->max('portfolios_count') ?: 1;
        $minCount = $tags->min('portfolios_count') ?: 1;
        $range = $maxCount - $minCount;

        foreach ($tags as $tag) {
            if ($range > 0) {
                $size = 0.8 + ($tag->portfolios_count - $minCount) / $range * 1.2;
            } else {
                $size = 1;
            }
            $tag->size = round($size, 2);
        }

        return $tags;
    }

    /**
     * Check if tag has portfolios.
     */
    public function hasPortfolios()
    {
        return $this->portfolios()->count() > 0;
    }

    /**
     * Delete tag and handle relationships.
     */
    public function deleteWithRelations()
    {
        // Detach all portfolios
        $this->portfolios()->detach();

        return $this->delete();
    }

    /**
     * Get tag statistics.
     */
    public function getStatistics()
    {
        return [
            'total_portfolios' => $this->allPortfolios()->count(),
            'active_portfolios' => $this->portfolios()->count(),
            'color' => $this->color,
            'color_class' => $this->color_class,
        ];
    }

    /**
     * Get the latest portfolios with this tag.
     */
    public function getLatestPortfolios($limit = 4)
    {
        return $this->portfolios()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get the most viewed portfolios with this tag.
     */
    public function getPopularPortfolios($limit = 4)
    {
        return $this->portfolios()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get related tags based on shared portfolios.
     */
    public function getRelatedTags($limit = 5)
    {
        $portfolioIds = $this->portfolios()->pluck('portfolios.id');

        return self::whereHas('portfolios', function ($query) use ($portfolioIds) {
                $query->whereIn('portfolios.id', $portfolioIds);
            })
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->withCount(['portfolios' => function ($query) use ($portfolioIds) {
                $query->whereIn('portfolios.id', $portfolioIds);
            }])
            ->orderBy('portfolios_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get formatted tag name with hashtag.
     */
    public function getHashtagAttribute()
    {
        return '#' . $this->name;
    }

    /**
     * Get the display name with icon.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->icon) {
            return '<i class="fas ' . $this->icon . '"></i> ' . $this->name;
        }
        return $this->name;
    }

    /**
     * Get tag URL for admin panel.
     */
    public function getAdminUrlAttribute()
    {
        return route('admin.portfolio.tags.edit', $this->id);
    }

    /**
     * Scope to get tags by portfolio count.
     */
    public function scopeWithPortfolioCount($query, $minCount = 1)
    {
        return $query->withCount('portfolios')
            ->having('portfolios_count', '>=', $minCount);
    }

    /**
     * Get random tags.
     */
    public static function getRandomTags($count = 5)
    {
        return self::where('is_active', true)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }

    /**
     * Check if tag name is already taken.
     */
    public static function isNameTaken($name, $excludeId = null)
    {
        $query = self::where('name', $name);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    /**
     * Get tag suggestions based on input.
     */
    public static function getSuggestions($query, $limit = 10)
    {
        return self::where('name', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }
}