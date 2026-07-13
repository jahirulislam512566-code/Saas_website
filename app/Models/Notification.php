<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'action_url',
        'action_text',
        'is_read',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markAsUnread()
    {
        $this->update(['is_read' => false, 'read_at' => null]);
    }

    public function isRead()
    {
        return $this->is_read;
    }

    public function isUnread()
    {
        return !$this->is_read;
    }
}