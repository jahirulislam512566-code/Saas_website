<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'data',
        'ip_address',
        'user_agent',
        'status',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['read_at' => now(), 'status' => 'read']);
    }
}