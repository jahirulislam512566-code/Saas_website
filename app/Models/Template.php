<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'category_slug',
        'description',
        'image_url',
        'demo_url',
        'price',
        'pages',
        'features',
        'colors',
        'rating',
        'reviews',
        'usage_count',
        'popular',
        'is_active',
        'is_free',
        'default_settings',
        'sections',
        'pages_data',
    ];

    protected $casts = [
        'features' => 'array',
        'colors' => 'array',
        'default_settings' => 'array',
        'sections' => 'array',
        'pages_data' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'usage_count' => 'integer',
        'popular' => 'boolean',
        'is_active' => 'boolean',
        'is_free' => 'boolean',
    ];

    protected $appends = ['is_new', 'formatted_price'];

    public function getIsNewAttribute()
    {
        return $this->created_at->diffInDays(now()) < 30;
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price == 0 ? 'Free' : '$' . number_format($this->price, 2);
    }

    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    public function scopePopular($query)
    {
        return $query->where('popular', true)->orderBy('usage_count', 'desc');
    }

    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    public function scopePremium($query)
    {
        return $query->where('price', '>', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category_slug', $category);
    }
}