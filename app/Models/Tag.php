<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'slug',
        'description',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag')->withTimestamps();
    }

    // Scopes
    public function scopePopular($query)
    {
        return $query->withCount('posts')->orderBy('posts_count', 'desc');
    }
}