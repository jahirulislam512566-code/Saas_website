<?php

namespace App\Traits\Model;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSourceColumn())) {
                $model->slug = $model->generateSlug();
            }
        });
    }

    /**
     * Get the slug source column.
     */
    protected function getSlugSourceColumn(): string
    {
        return $this->slugSource ?? 'title';
    }

    /**
     * Get the slug column.
     */
    protected function getSlugColumn(): string
    {
        return $this->slugColumn ?? 'slug';
    }

    /**
     * Generate a unique slug.
     */
    public function generateSlug(): string
    {
        $source = $this->{$this->getSlugSourceColumn()};
        $slug = Str::slug($source);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists.
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::where($this->getSlugColumn(), $slug);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        return $query->exists();
    }

    /**
     * Find a model by its slug.
     */
    public static function findBySlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Find a model by its slug or fail.
     */
    public static function findBySlugOrFail(string $slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    /**
     * Scope a query to filter by slug.
     */
    public function scopeWhereSlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get route key name.
     */
    public function getRouteKeyName(): string
    {
        return $this->getSlugColumn();
    }
}