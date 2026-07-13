<?php
// app/Models/SEO.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SEO extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'model_type',
        'model_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_card',
        'canonical_url',
        'robots',
        'json_ld',
        'h1',
        'h2',
        'focus_keyword',
        'secondary_keywords',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta_keywords' => 'array',
        'secondary_keywords' => 'array',
        'robots' => 'array',
        'json_ld' => 'array',
        'is_active' => 'boolean',
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
        'meta_title_fallback',
        'meta_description_fallback',
        'formatted_keywords',
        'is_robots_index',
        'is_robots_follow',
        'og_image_url',
        'twitter_image_url',
        'score',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the tenant that owns the SEO settings.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the parent model (page, post, etc.)
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the SEO settings.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the SEO settings.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope a query to only include SEO settings for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include active SEO settings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include SEO settings for a specific model type.
     */
    public function scopeForModel($query, $type, $id)
    {
        return $query->where('model_type', $type)
                    ->where('model_id', $id);
    }

    /**
     * Scope a query to search SEO settings.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('meta_title', 'LIKE', "%{$search}%")
              ->orWhere('meta_description', 'LIKE', "%{$search}%")
              ->orWhere('focus_keyword', 'LIKE', "%{$search}%")
              ->orWhere('meta_keywords', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to only include SEO settings with missing meta titles.
     */
    public function scopeMissingMetaTitle($query)
    {
        return $query->whereNull('meta_title')->orWhere('meta_title', '');
    }

    /**
     * Scope a query to only include SEO settings with missing meta descriptions.
     */
    public function scopeMissingMetaDescription($query)
    {
        return $query->whereNull('meta_description')->orWhere('meta_description', '');
    }

    /**
     * Scope a query to only include SEO settings with short meta titles.
     */
    public function scopeShortMetaTitle($query)
    {
        return $query->whereRaw('LENGTH(meta_title) < 30');
    }

    /**
     * Scope a query to only include SEO settings with long meta titles.
     */
    public function scopeLongMetaTitle($query)
    {
        return $query->whereRaw('LENGTH(meta_title) > 60');
    }

    /**
     * Scope a query to only include SEO settings with short meta descriptions.
     */
    public function scopeShortMetaDescription($query)
    {
        return $query->whereRaw('LENGTH(meta_description) < 120');
    }

    /**
     * Scope a query to only include SEO settings with long meta descriptions.
     */
    public function scopeLongMetaDescription($query)
    {
        return $query->whereRaw('LENGTH(meta_description) > 160');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Get fallback meta title if not set.
     */
    public function getMetaTitleFallbackAttribute(): string
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }

        // Try to get from parent model
        if ($this->model) {
            if (method_exists($this->model, 'getMetaTitleAttribute')) {
                return $this->model->meta_title;
            }
            if (isset($this->model->name)) {
                return $this->model->name;
            }
            if (isset($this->model->title)) {
                return $this->model->title;
            }
        }

        return config('app.name') . ' - ' . class_basename($this->model_type ?? '');
    }

    /**
     * Get fallback meta description if not set.
     */
    public function getMetaDescriptionFallbackAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        // Try to get from parent model
        if ($this->model) {
            if (method_exists($this->model, 'getMetaDescriptionAttribute')) {
                return $this->model->meta_description;
            }
            if (isset($this->model->description)) {
                return $this->model->description;
            }
            if (isset($this->model->excerpt)) {
                return $this->model->excerpt;
            }
        }

        return '';
    }

    /**
     * Get formatted keywords as string.
     */
    public function getFormattedKeywordsAttribute(): string
    {
        if (is_array($this->meta_keywords)) {
            return implode(', ', $this->meta_keywords);
        }
        return $this->meta_keywords ?? '';
    }

    /**
     * Get robots index status.
     */
    public function getIsRobotsIndexAttribute(): bool
    {
        $robots = $this->robots ?? [];
        return !in_array('noindex', $robots);
    }

    /**
     * Get robots follow status.
     */
    public function getIsRobotsFollowAttribute(): bool
    {
        $robots = $this->robots ?? [];
        return !in_array('nofollow', $robots);
    }

    /**
     * Get OG image URL.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        if ($this->og_image) {
            if (filter_var($this->og_image, FILTER_VALIDATE_URL)) {
                return $this->og_image;
            }
            return asset('storage/' . $this->og_image);
        }
        return null;
    }

    /**
     * Get Twitter image URL.
     */
    public function getTwitterImageUrlAttribute(): ?string
    {
        if ($this->twitter_image) {
            if (filter_var($this->twitter_image, FILTER_VALIDATE_URL)) {
                return $this->twitter_image;
            }
            return asset('storage/' . $this->twitter_image);
        }
        return null;
    }

    /**
     * Get SEO score.
     */
    public function getScoreAttribute(): int
    {
        $score = 100;

        // Meta Title checks
        if (empty($this->meta_title)) {
            $score -= 15;
        } elseif (strlen($this->meta_title) < 30) {
            $score -= 10;
        } elseif (strlen($this->meta_title) > 60) {
            $score -= 5;
        }

        // Meta Description checks
        if (empty($this->meta_description)) {
            $score -= 15;
        } elseif (strlen($this->meta_description) < 120) {
            $score -= 10;
        } elseif (strlen($this->meta_description) > 160) {
            $score -= 5;
        }

        // Focus Keyword check
        if (empty($this->focus_keyword)) {
            $score -= 10;
        }

        // OG tags check
        if (empty($this->og_title) || empty($this->og_description)) {
            $score -= 5;
        }

        // Robots check
        if (!empty($this->robots) && in_array('noindex', $this->robots)) {
            $score -= 10;
        }

        // Canonical URL check
        if (empty($this->canonical_url)) {
            $score -= 5;
        }

        return max(0, $score);
    }

    // ============================================
    // MUTATORS
    // ============================================

    /**
     * Set meta keywords as array.
     */
    public function setMetaKeywordsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['meta_keywords'] = json_encode(
                array_map('trim', explode(',', $value))
            );
        } else {
            $this->attributes['meta_keywords'] = json_encode($value);
        }
    }

    /**
     * Set robots as array.
     */
    public function setRobotsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['robots'] = json_encode(
                array_map('trim', explode(',', $value))
            );
        } else {
            $this->attributes['robots'] = json_encode($value);
        }
    }

    /**
     * Set JSON-LD as array.
     */
    public function setJsonLdAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['json_ld'] = json_decode($value, true) ?: $value;
        } else {
            $this->attributes['json_ld'] = json_encode($value);
        }
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    /**
     * Get the full meta title with site name.
     */
    public function getFullMetaTitle(): string
    {
        $title = $this->meta_title ?? $this->meta_title_fallback;
        $siteName = config('app.name');
        
        if (!str_contains($title, $siteName)) {
            return $title . ' | ' . $siteName;
        }
        
        return $title;
    }

    /**
     * Get Open Graph tags array.
     */
    public function getOpenGraphTags(): array
    {
        $tags = [
            'og:title' => $this->og_title ?? $this->meta_title_fallback,
            'og:description' => $this->og_description ?? $this->meta_description_fallback,
            'og:type' => $this->og_type ?? 'website',
            'og:url' => $this->model ? $this->model->getUrl() : url()->current(),
        ];

        if ($this->og_image_url) {
            $tags['og:image'] = $this->og_image_url;
            $tags['og:image:width'] = '1200';
            $tags['og:image:height'] = '630';
        }

        return $tags;
    }

    /**
     * Get Twitter Card tags array.
     */
    public function getTwitterCardTags(): array
    {
        $tags = [
            'twitter:card' => $this->twitter_card ?? 'summary_large_image',
            'twitter:title' => $this->twitter_title ?? $this->meta_title_fallback,
            'twitter:description' => $this->twitter_description ?? $this->meta_description_fallback,
        ];

        if ($this->twitter_image_url) {
            $tags['twitter:image'] = $this->twitter_image_url;
        }

        return $tags;
    }

    /**
     * Get all structured data as JSON-LD.
     */
    public function getStructuredData(): array
    {
        return $this->json_ld ?? [];
    }

    /**
     * Generate meta tags HTML.
     */
    public function generateMetaTags(): string
    {
        $html = '';

        // Basic meta tags
        $html .= '<title>' . e($this->getFullMetaTitle()) . '</title>' . "\n";
        $html .= '<meta name="description" content="' . e($this->meta_description_fallback) . '">' . "\n";
        
        if ($this->meta_keywords) {
            $html .= '<meta name="keywords" content="' . e($this->formatted_keywords) . '">' . "\n";
        }

        // Canonical URL
        if ($this->canonical_url) {
            $html .= '<link rel="canonical" href="' . e($this->canonical_url) . '">' . "\n";
        }

        // Robots meta tag
        if ($this->robots) {
            $html .= '<meta name="robots" content="' . e(implode(', ', $this->robots)) . '">' . "\n";
        }

        // Open Graph tags
        foreach ($this->getOpenGraphTags() as $property => $content) {
            $html .= '<meta property="' . e($property) . '" content="' . e($content) . '">' . "\n";
        }

        // Twitter Card tags
        foreach ($this->getTwitterCardTags() as $name => $content) {
            $html .= '<meta name="' . e($name) . '" content="' . e($content) . '">' . "\n";
        }

        // JSON-LD structured data
        if ($this->json_ld) {
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($this->json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $html .= "\n</script>\n";
        }

        return $html;
    }

    /**
     * Check if SEO settings are complete.
     */
    public function isComplete(): bool
    {
        return !empty($this->meta_title) 
            && !empty($this->meta_description) 
            && !empty($this->focus_keyword);
    }

    /**
     * Get SEO issues.
     */
    public function getIssues(): array
    {
        $issues = [];

        if (empty($this->meta_title)) {
            $issues[] = [
                'severity' => 'critical',
                'message' => 'Meta title is missing',
                'field' => 'meta_title',
            ];
        } elseif (strlen($this->meta_title) < 30) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Meta title is too short (' . strlen($this->meta_title) . ' characters)',
                'field' => 'meta_title',
            ];
        } elseif (strlen($this->meta_title) > 60) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Meta title is too long (' . strlen($this->meta_title) . ' characters)',
                'field' => 'meta_title',
            ];
        }

        if (empty($this->meta_description)) {
            $issues[] = [
                'severity' => 'critical',
                'message' => 'Meta description is missing',
                'field' => 'meta_description',
            ];
        } elseif (strlen($this->meta_description) < 120) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Meta description is too short (' . strlen($this->meta_description) . ' characters)',
                'field' => 'meta_description',
            ];
        } elseif (strlen($this->meta_description) > 160) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Meta description is too long (' . strlen($this->meta_description) . ' characters)',
                'field' => 'meta_description',
            ];
        }

        if (empty($this->focus_keyword)) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Focus keyword is not set',
                'field' => 'focus_keyword',
            ];
        }

        if (empty($this->og_title) || empty($this->og_description)) {
            $issues[] = [
                'severity' => 'info',
                'message' => 'Open Graph tags are incomplete',
                'field' => 'og_tags',
            ];
        }

        if (!empty($this->robots) && in_array('noindex', $this->robots)) {
            $issues[] = [
                'severity' => 'critical',
                'message' => 'Page is set to noindex',
                'field' => 'robots',
            ];
        }

        return $issues;
    }

    /**
     * Get SEO recommendations.
     */
    public function getRecommendations(): array
    {
        $recommendations = [];

        if (empty($this->meta_title)) {
            $recommendations[] = 'Add a meta title between 30-60 characters';
        } elseif (strlen($this->meta_title) < 30) {
            $recommendations[] = 'Expand meta title to at least 30 characters';
        } elseif (strlen($this->meta_title) > 60) {
            $recommendations[] = 'Shorten meta title to under 60 characters';
        }

        if (empty($this->meta_description)) {
            $recommendations[] = 'Add a meta description between 120-160 characters';
        } elseif (strlen($this->meta_description) < 120) {
            $recommendations[] = 'Expand meta description to at least 120 characters';
        } elseif (strlen($this->meta_description) > 160) {
            $recommendations[] = 'Shorten meta description to under 160 characters';
        }

        if (empty($this->focus_keyword)) {
            $recommendations[] = 'Set a focus keyword for this page';
        }

        if (empty($this->og_title) || empty($this->og_description)) {
            $recommendations[] = 'Add Open Graph tags for better social sharing';
        }

        return $recommendations;
    }

    /**
     * Get the model's URL.
     */
    public function getModelUrl(): ?string
    {
        if ($this->model && method_exists($this->model, 'getUrl')) {
            return $this->model->getUrl();
        }
        return null;
    }

    /**
     * Get the model's name.
     */
    public function getModelName(): ?string
    {
        if ($this->model) {
            if (isset($this->model->name)) {
                return $this->model->name;
            }
            if (isset($this->model->title)) {
                return $this->model->title;
            }
        }
        return null;
    }

    // ============================================
    // STATIC METHODS
    // ============================================

    /**
     * Create or update SEO settings for a model.
     */
    public static function updateForModel($model, array $data): self
    {
        $seo = self::firstOrNew([
            'model_type' => get_class($model),
            'model_id' => $model->id,
        ]);

        $seo->fill($data);
        $seo->save();

        return $seo;
    }

    /**
     * Get SEO settings for a model.
     */
    public static function getForModel($model): ?self
    {
        return self::where('model_type', get_class($model))
                   ->where('model_id', $model->id)
                   ->first();
    }

    /**
     * Get or create SEO settings for a model.
     */
    public static function getOrCreateForModel($model): self
    {
        $seo = self::getForModel($model);
        
        if (!$seo) {
            $seo = self::create([
                'tenant_id' => $model->tenant_id ?? auth()->user()->tenant_id,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'meta_title' => $model->title ?? $model->name ?? null,
                'meta_description' => $model->excerpt ?? $model->description ?? null,
                'created_by' => auth()->id(),
            ]);
        }

        return $seo;
    }

    // ============================================
    // BOOT METHODS
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->tenant_id) && auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });

        static::created(function ($model) {
            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $model->tenant_id,
                'subject_type' => self::class,
                'subject_id' => $model->id,
                'action' => 'created_seo',
                'description' => 'Created SEO settings for ' . class_basename($model->model_type ?? 'Unknown'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        static::updated(function ($model) {
            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $model->tenant_id,
                'subject_type' => self::class,
                'subject_id' => $model->id,
                'action' => 'updated_seo',
                'description' => 'Updated SEO settings for ' . class_basename($model->model_type ?? 'Unknown'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}