<?php
// app/Models/Domain.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'website_id',
        'domain',
        'status',
        'is_primary',
        'ssl_enabled',
        'ssl_expires_at',
        'verified_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'ssl_enabled' => 'boolean',
        'ssl_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    public function hasSSL(): bool
    {
        return $this->ssl_enabled;
    }

    public function isSSLExpiring(): bool
    {
        if (!$this->ssl_expires_at) {
            return false;
        }
        return $this->ssl_expires_at->diffInDays(now()) <= 30;
    }
}