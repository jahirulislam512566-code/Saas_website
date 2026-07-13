<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'gallery_images',
        'client_name',
        'project_date',
        'project_url',
        'technologies',
        'services',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'technologies' => 'array',
        'services' => 'array',
        'project_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'portfolio_category')->withTimestamps();
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
    public function getTechnologiesListAttribute()
    {
        return is_array($this->technologies) ? implode(', ', $this->technologies) : '';
    }

    public function getGalleryImagesUrlsAttribute()
    {
        if (!$this->gallery_images) return [];
        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->gallery_images);
    }
}