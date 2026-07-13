<?php
// app/Models/PageComponent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'template_component_id',
        'type',
        'name',
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

    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class);
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
            'text' => 'Text Block',
            'image' => 'Image Block',
            'video' => 'Video Block',
            'button' => 'Button',
            'icon' => 'Icon Block',
            'card' => 'Card',
            'testimonial' => 'Testimonial',
            'counter' => 'Counter',
            'slider' => 'Slider',
            'form' => 'Form',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'text' => 'fa-paragraph',
            'image' => 'fa-image',
            'video' => 'fa-video',
            'button' => 'fa-link',
            'icon' => 'fa-icons',
            'card' => 'fa-credit-card',
            'testimonial' => 'fa-quote-left',
            'counter' => 'fa-sort-numeric-up',
            'slider' => 'fa-sliders-h',
            'form' => 'fa-wpforms',
        ];
        return $icons[$this->type] ?? 'fa-cube';
    }
}