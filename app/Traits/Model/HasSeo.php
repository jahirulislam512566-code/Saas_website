<?php

namespace App\Traits\Model;

trait HasSeo
{
    /**
     * Get SEO data.
     */
    public function getSeoData(): array
    {
        return [
            'title' => $this->getSeoTitle(),
            'description' => $this->getSeoDescription(),
            'keywords' => $this->getSeoKeywords(),
            'image' => $this->getSeoImage(),
            'canonical' => $this->getSeoCanonical(),
            'schema' => $this->getSeoSchema(),
        ];
    }

    /**
     * Get SEO title.
     */
    public function getSeoTitle(): string
    {
        return $this->seo_title ?? $this->title ?? $this->name ?? '';
    }

    /**
     * Get SEO description.
     */
    public function getSeoDescription(): ?string
    {
        return $this->seo_description ?? $this->excerpt ?? $this->description ?? null;
    }

    /**
     * Get SEO keywords.
     */
    public function getSeoKeywords(): ?string
    {
        return $this->seo_keywords ?? null;
    }

    /**
     * Get SEO image.
     */
    public function getSeoImage(): ?string
    {
        return $this->seo_image ?? $this->featured_image ?? null;
    }

    /**
     * Get SEO canonical URL.
     */
    public function getSeoCanonical(): ?string
    {
        return $this->seo_canonical ?? url()->current();
    }

    /**
     * Get SEO schema.
     */
    public function getSeoSchema(): ?array
    {
        return $this->seo_schema ?? null;
    }

    /**
     * Update SEO data.
     */
    public function updateSeo(array $data): bool
    {
        return $this->update([
            'seo_title' => $data['title'] ?? $this->seo_title,
            'seo_description' => $data['description'] ?? $this->seo_description,
            'seo_keywords' => $data['keywords'] ?? $this->seo_keywords,
            'seo_image' => $data['image'] ?? $this->seo_image,
            'seo_canonical' => $data['canonical'] ?? $this->seo_canonical,
            'seo_schema' => $data['schema'] ?? $this->seo_schema,
        ]);
    }

    /**
     * Scope to filter by SEO title.
     */
    public function scopeWhereSeoTitle($query, string $title)
    {
        return $query->where('seo_title', 'LIKE', "%{$title}%");
    }
}