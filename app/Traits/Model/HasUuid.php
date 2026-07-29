<?php

namespace App\Traits\Model;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the trait.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the UUID column.
     */
    protected function getUuidColumn(): string
    {
        return $this->uuidColumn ?? 'uuid';
    }

    /**
     * Find a model by its UUID.
     */
    public static function findByUuid(string $uuid)
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * Find a model by its UUID or fail.
     */
    public static function findByUuidOrFail(string $uuid)
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Scope a query to filter by UUID.
     */
    public function scopeWhereUuid($query, string $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    /**
     * Get route key name for UUID.
     */
    public function getRouteKeyName(): string
    {
        return $this->getUuidColumn();
    }
}