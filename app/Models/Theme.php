<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'thumbnail',
        'version',
        'config',
        'custom_css',
        'custom_js',
        'is_active',
        'is_default',
        'parent_theme',
    ];

    protected $casts = [
        'config' => 'array',
        'custom_css' => 'array',
        'custom_js' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function websites()
    {
        return $this->belongsToMany(Website::class, 'website_theme')
            ->withPivot('settings')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helper methods
    public function getPreviewUrlAttribute()
    {
        return $this->preview_image ? asset('storage/' . $this->preview_image) : null;
    }
}