<?php
// app/Models/PageSection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'page_id',
        'template_section_id',
        'name',
        'type',
        'content',
        'settings',
        'order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(PageComponent::class)->orderBy('order');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'hero' => 'Hero Section',
            'features' => 'Features Section',
            'content' => 'Content Section',
            'gallery' => 'Gallery Section',
            'testimonials' => 'Testimonials Section',
            'pricing' => 'Pricing Section',
            'contact' => 'Contact Section',
            'footer' => 'Footer Section',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'hero' => 'fa-home',
            'features' => 'fa-star',
            'content' => 'fa-file-alt',
            'gallery' => 'fa-images',
            'testimonials' => 'fa-quote-right',
            'pricing' => 'fa-dollar-sign',
            'contact' => 'fa-envelope',
            'footer' => 'fa-phone',
        ];
        return $icons[$this->type] ?? 'fa-cube';
    }
}