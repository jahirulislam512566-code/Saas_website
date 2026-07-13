<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'title',
        'bio',
        'avatar',
        'email',
        'phone',
        'social_links',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
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

    // Accessors
    public function getSocialLinksArrayAttribute()
    {
        if (!$this->social_links) {
            return [
                'facebook' => null,
                'twitter' => null,
                'linkedin' => null,
                'instagram' => null,
                'youtube' => null,
            ];
        }
        return $this->social_links;
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }
}