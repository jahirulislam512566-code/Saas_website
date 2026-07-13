<?php
// app/Models/Webhook.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'url',
        'events',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function trigger($event, $payload)
    {
        if (!$this->is_active || !in_array($event, $this->events)) {
            return false;
        }

        // In production, this would queue the webhook for async processing
        $this->processWebhook($event, $payload);

        return true;
    }

    public function processWebhook($event, $payload)
    {
        $data = [
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'data' => $payload,
        ];

        try {
            $response = Http::timeout(10)
                ->post($this->url, $data);

            WebhookLog::create([
                'tenant_id' => $this->tenant_id,
                'webhook_id' => $this->id,
                'event' => $event,
                'payload' => $data,
                'response' => [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ],
                'status' => $response->successful() ? 'success' : 'failed',
            ]);

            return $response;
        } catch (\Exception $e) {
            WebhookLog::create([
                'tenant_id' => $this->tenant_id,
                'webhook_id' => $this->id,
                'event' => $event,
                'payload' => $data,
                'response' => [
                    'error' => $e->getMessage(),
                ],
                'status' => 'failed',
            ]);

            return null;
        }
    }
}