<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'thumbnail',
        'config',
        'default_data',
        'category',
        'version',
        'is_free',
        'price',
        'downloads',
        'rating',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'default_data' => 'array',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'downloads' => 'integer',
        'rating' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_free', false);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getPreviewImageUrlAttribute()
    {
        return $this->preview_image ? asset('storage/' . $this->preview_image) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }

    public function getPriceFormattedAttribute()
    {
        if ($this->is_free) {
            return 'Free';
        }
        return '$' . number_format($this->price, 2);
    }
}