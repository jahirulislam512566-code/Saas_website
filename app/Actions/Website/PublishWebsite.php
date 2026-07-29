<?php

namespace App\Actions\Website;

use App\Actions\BaseAction;
use App\Models\Website;
use App\Services\SEO\SitemapService;
use Illuminate\Support\Facades\Cache;

class PublishWebsite extends BaseAction
{
    protected array $validationRules = [
        'website_id' => 'required|exists:websites,id',
        'make_public' => 'nullable|boolean',
        'generate_sitemap' => 'nullable|boolean',
        'clear_cache' => 'nullable|boolean',
    ];

    protected SitemapService $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    public function execute(array $data): Website
    {
        $this->data = $data;
        $this->validate($data);

        $this->beginTransaction();

        try {
            $website = Website::findOrFail($data['website_id']);

            // Validate before publishing
            $this->validatePublish($website);

            // Update status
            $updateData = [
                'status' => 'published',
                'published_at' => now(),
                'is_public' => $data['make_public'] ?? true,
            ];

            $website->update($updateData);

            // Generate sitemap
            if ($data['generate_sitemap'] ?? true) {
                $this->sitemapService->generateSitemap($website);
            }

            // Clear cache
            if ($data['clear_cache'] ?? true) {
                Cache::forget('website_' . $website->id);
                Cache::forget('user_websites_' . $website->user_id);
                Cache::tags(['website_' . $website->id])->flush();
            }

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\WebsitePublished', [
                'website' => $website,
            ]);

            return $website->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to publish website', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function validatePublish(Website $website): void
    {
        // Check if website has a home page
        if (!$website->pages()->where('is_home', true)->exists()) {
            throw new ActionException(
                'Website must have a home page to publish.',
                ['website_id' => $website->id]
            );
        }

        // Check if website has at least one published page
        if ($website->pages()->where('status', 'published')->count() === 0) {
            throw new ActionException(
                'Website must have at least one published page to publish.',
                ['website_id' => $website->id]
            );
        }

        // Check if website has a valid domain
        if (!$website->domain && !$website->slug) {
            throw new ActionException(
                'Website must have a valid domain or slug to publish.',
                ['website_id' => $website->id]
            );
        }
    }
}