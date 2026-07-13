<?php

namespace App\Models;

use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'slug',
        'description',
        'fields',
        'validation_rules',
        'success_message',
        'redirect_url',
        'recipient_email',
        'send_confirmation',
        'confirmation_email',
        'is_active',
    ];

    protected $casts = [
        'fields' => 'array',
        'validation_rules' => 'array',
        'confirmation_email' => 'array',
        'send_confirmation' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}