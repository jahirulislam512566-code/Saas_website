<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'name',
        'type',
        'title',
        'subtitle',
        'description',
        'background_color',
        'background_image',
        'text_color',
        'settings',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class)->orderBy('sort_order');
    }
}