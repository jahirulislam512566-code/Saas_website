<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'slug',
        'description',
        'content',
        'icon',
        'image',
        'featured_image',
        'price',
        'duration',
        'features',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessors
    public function getPriceFormattedAttribute()
    {
        return $this->price ? '$' . number_format($this->price, 2) : 'Contact us';
    }

    public function getFeaturesListAttribute()
    {
        return is_array($this->features) ? implode(', ', $this->features) : '';
    }
}