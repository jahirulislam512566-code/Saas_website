<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'department_id',
        'assigned_to',
        'ticket_number',
        'subject',
        'message',
        'priority',
        'status',
        'category',
        'attachments',
        'resolved_at',
        'closed_at',
        'response_time',
        'resolution_time',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'response_time' => 'integer',
        'resolution_time' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => 'primary',
            'pending' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'info',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'danger',
            default => 'info',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'open' => 'bg-blue-100 text-blue-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}