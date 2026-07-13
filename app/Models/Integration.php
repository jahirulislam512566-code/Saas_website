<?php
// app/Models/Integration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'integrations';

    protected $fillable = [
        'tenant_id',
        'name',
        'config',
        'is_enabled',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'config' => 'array',
        'is_enabled' => 'boolean',
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
}