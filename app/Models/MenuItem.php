<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'route',
        'parameters',
        'icon',
        'target',
        'attributes',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'parameters' => 'array',
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    // Helper methods
    public function getUrlAttribute()
    {
        if ($this->route) {
            return route($this->route, $this->parameters ?? []);
        }
        return $this->url;
    }
}