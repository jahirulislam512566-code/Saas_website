<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'slug',
        'description',
        'cover_image',
        'type',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'gallery_media')
            ->withPivot('sort_order', 'metadata')
            ->withTimestamps()
            ->orderBy('gallery_media.sort_order');
    }

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) return null;
        return asset('storage/' . $this->cover_image);
    }

    public function getMediaCountAttribute()
    {
        return $this->media()->count();
    }
}