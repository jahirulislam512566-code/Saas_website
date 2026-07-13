<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'client_name',
        'client_title',
        'client_company',
        'client_avatar',
        'content',
        'rating',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
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

    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Accessors
    public function getClientNameFullAttribute()
    {
        $parts = [];
        if ($this->client_name) $parts[] = $this->client_name;
        if ($this->client_title) $parts[] = $this->client_title;
        if ($this->client_company) $parts[] = 'at ' . $this->client_company;
        return implode(' ', $parts);
    }

    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating ?? 0);
    }
}