<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'metadata',
        'ip_address',
        'user_agent',
        'status',
        'read_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
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

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['read_at' => now(), 'status' => 'read']);
    }

    public function markAsSpam()
    {
        $this->update(['status' => 'spam']);
    }

    public function isRead()
    {
        return $this->read_at !== null || $this->status === 'read';
    }

    public function isNew()
    {
        return $this->status === 'new';
    }
}