<?php
// app/Models/SchemaMarkup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemaMarkup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schema_markups';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'description',
        'schema_data',
        'target_pages',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'schema_data' => 'array',
        'target_pages' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'icon',
        'pages_count',
        'validation_status',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('type', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getIconAttribute(): string
    {
        $icons = [
            'Article' => 'fa-newspaper',
            'BlogPosting' => 'fa-blog',
            'Product' => 'fa-box',
            'Service' => 'fa-cog',
            'FAQ' => 'fa-question-circle',
            'HowTo' => 'fa-book',
            'Person' => 'fa-user',
            'Organization' => 'fa-building',
            'LocalBusiness' => 'fa-store',
            'Review' => 'fa-star',
            'Event' => 'fa-calendar',
            'Recipe' => 'fa-utensils',
        ];
        return $icons[$this->type] ?? 'fa-code';
    }

    public function getPagesCountAttribute(): int
    {
        return is_array($this->target_pages) ? count($this->target_pages) : 0;
    }

    public function getValidationStatusAttribute(): array
    {
        $issues = [];
        $schema = $this->schema_data;

        // Basic validation
        if (empty($schema)) {
            $issues[] = 'Schema data is empty';
        } elseif (!isset($schema['@context'])) {
            $issues[] = 'Missing @context property';
        } elseif (!isset($schema['@type'])) {
            $issues[] = 'Missing @type property';
        }

        return [
            'valid' => count($issues) === 0,
            'issues' => $issues,
        ];
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    /**
     * Generate full schema markup.
     */
    public function generateMarkup(): string
    {
        if (!$this->is_active || empty($this->schema_data)) {
            return '';
        }

        return '<script type="application/ld+json">' . "\n" .
               json_encode($this->schema_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) .
               "\n</script>";
    }

    /**
     * Validate schema data.
     */
    public function validateSchema(): array
    {
        $schema = $this->schema_data;
        $errors = [];

        // Check required properties
        $required = ['@context', '@type'];
        foreach ($required as $field) {
            if (!isset($schema[$field])) {
                $errors[] = "Missing required field: {$field}";
            }
        }

        // Check @context value
        if (isset($schema['@context']) && $schema['@context'] !== 'https://schema.org') {
            $errors[] = "@context should be 'https://schema.org'";
        }

        // Check @type
        $validTypes = [
            'Article', 'BlogPosting', 'Product', 'Service', 'FAQ', 
            'HowTo', 'Person', 'Organization', 'LocalBusiness', 
            'Review', 'Event', 'Recipe'
        ];
        
        if (isset($schema['@type']) && !in_array($schema['@type'], $validTypes)) {
            $errors[] = "Invalid @type. Must be one of: " . implode(', ', $validTypes);
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    /**
     * Get preview HTML.
     */
    public function getPreview(): string
    {
        return $this->generateMarkup();
    }

    /**
     * Duplicate schema.
     */
    public function duplicate(): self
    {
        $newSchema = $this->replicate();
        $newSchema->name = $this->name . ' (Copy)';
        $newSchema->is_active = false;
        $newSchema->created_by = auth()->id();
        $newSchema->save();

        return $newSchema;
    }

    // ============================================
    // BOOT METHODS
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $model->tenant_id,
                'subject_type' => self::class,
                'subject_id' => $model->id,
                'action' => 'created_schema',
                'description' => "Created schema markup: {$model->name}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        static::updated(function ($model) {
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $model->tenant_id,
                'subject_type' => self::class,
                'subject_id' => $model->id,
                'action' => 'updated_schema',
                'description' => "Updated schema markup: {$model->name}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        static::deleted(function ($model) {
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $model->tenant_id,
                'subject_type' => self::class,
                'subject_id' => $model->id,
                'action' => 'deleted_schema',
                'description' => "Deleted schema markup: {$model->name}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}