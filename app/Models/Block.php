<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'name',
        'type',
        'content',
        'settings',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}