<?php
// app/Models/PaymentGateway.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_gateways';

    protected $fillable = [
        'tenant_id',
        'gateway',
        'is_enabled',
        'mode',
        'api_key',
        'api_secret',
        'webhook_secret',
        'currencies',
        'settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'currencies' => 'array',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function isLive(): bool
    {
        return $this->mode === 'live';
    }

    public function isTest(): bool
    {
        return $this->mode === 'test' || $this->mode === 'sandbox';
    }
}