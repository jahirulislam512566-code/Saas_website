<?php

namespace App\Actions\SEO;

use App\Actions\BaseAction;
use App\Models\Website;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends BaseAction
{
    protected array $validationRules = [
        'website_id' => 'required|exists:websites,id',
        'include_posts' => 'nullable|boolean',
        'include_pages' => 'nullable|boolean',
        'home_priority' => 'nullable|numeric|between:0,1',
        'page_priority' => 'nullable|numeric|between:0,1',
        'post_priority' => 'nullable|numeric|between:0,1',
        'home_changefreq' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
        'page_changefreq' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
        'post_changefreq' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
        'ping_engines' => 'nullable|boolean',
        'save_to_disk' => 'nullable|boolean',
    ];

    public function execute(array $data): array
    {
        $this->data = $data;
        $this->validate($data);

        try {
            $website = Website::findOrFail($data['website_id']);

            // Get pages for sitemap
            $pages = $this->getSitemapPages($website, $data);

            // Generate sitemap XML
            $sitemap = $this->generateSitemapXML($website, $pages, $data);

            // Save sitemap
            $path = null;
            $url = null;

            if ($data['save_to_disk'] ?? true) {
                $path = $this->saveSitemap($website, $sitemap);
                $url = Storage::disk('public')->url($path);
            }

            // Ping search engines
            if ($data['ping_engines'] ?? true) {
                $this->pingSearchEngines($website, $url);
            }

            // Update website
            $website->update([
                'sitemap_generated_at' => now(),
                'sitemap_url' => $url,
            ]);

            // Clear cache
            Cache::forget('sitemap_' . $website->id);

            return [
                'sitemap' => $sitemap,
                'path' => $path,
                'url' => $url,
                'pages_count' => count($pages),
                'generated_at' => now()->toDateTimeString(),
            ];

        } catch (\Exception $e) {
            $this->logError('Failed to generate sitemap', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getSitemapPages(Website $website, array $options): array
    {
        $pages = [];

        // Add homepage
        if ($options['include_pages'] ?? true) {
            $homePage = $website->pages()->where('is_home', true)->first();
            if ($homePage) {
                $pages[] = [
                    'url' => '/',
                    'lastmod' => $homePage->updated_at->toIso8601String(),
                    'changefreq' => $options['home_changefreq'] ?? 'daily',
                    'priority' => $options['home_priority'] ?? '1.0',
                    'type' => 'home',
                ];
            }
        }

        // Add other pages
        if ($options['include_pages'] ?? true) {
            $query = $website->pages()->where('status', 'published');
            
            if ($homePage ?? false) {
                $query->where('id', '!=', $homePage->id);
            }
            
            $otherPages = $query->get();

            foreach ($otherPages as $page) {
                $pages[] = [
                    'url' => '/' . $page->slug,
                    'lastmod' => $page->updated_at->toIso8601String(),
                    'changefreq' => $options['page_changefreq'] ?? 'weekly',
                    'priority' => $options['page_priority'] ?? '0.8',
                    'type' => 'page',
                ];
            }
        }

        // Add blog posts
        if ($options['include_posts'] ?? true) {
            $posts = $website->posts()->where('status', 'published')->get();
            
            foreach ($posts as $post) {
                $pages[] = [
                    'url' => '/blog/' . $post->slug,
                    'lastmod' => $post->updated_at->toIso8601String(),
                    'changefreq' => $options['post_changefreq'] ?? 'daily',
                    'priority' => $options['post_priority'] ?? '0.6',
                    'type' => 'post',
                ];
            }
        }

        // Add custom URLs if provided
        if (isset($options['custom_urls']) && is_array($options['custom_urls'])) {
            $pages = array_merge($pages, $options['custom_urls']);
        }

        return $pages;
    }

    protected function generateSitemapXML(Website $website, array $pages, array $options): string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        foreach ($pages as $page) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $this->getPageUrl($website, $page['url']));
            $url->addChild('lastmod', $page['lastmod'] ?? now()->toIso8601String());
            $url->addChild('changefreq', $page['changefreq'] ?? 'weekly');
            $url->addChild('priority', $page['priority'] ?? '0.5');
            
            // Add image sitemap if available
            if (isset($page['images']) && is_array($page['images'])) {
                foreach ($page['images'] as $image) {
                    $imageElement = $url->addChild('image:image', '', 'http://www.google.com/schemas/sitemap-image/1.1');
                    $imageElement->addChild('image:loc', $image['loc'], 'http://www.google.com/schemas/sitemap-image/1.1');
                    if (isset($image['caption'])) {
                        $imageElement->addChild('image:caption', $image['caption'], 'http://www.google.com/schemas/sitemap-image/1.1');
                    }
                }
            }
        }

        return $xml->asXML();
    }

    protected function getPageUrl(Website $website, string $path): string
    {
        $domain = $website->domain ?? $website->slug . '.' . config('app.domain', 'example.com');
        $protocol = ($website->settings['ssl_enabled'] ?? false) ? 'https' : 'http';
        
        return $protocol . '://' . $domain . $path;
    }

    protected function saveSitemap(Website $website, string $sitemap): string
    {
        $filename = "sitemap_{$website->slug}.xml";
        $path = "websites/{$website->id}/sitemaps/{$filename}";
        
        Storage::disk('public')->put($path, $sitemap);
        
        return $path;
    }

    protected function pingSearchEngines(Website $website, string $sitemapUrl): void
    {
        $searchEngines = [
            'google' => 'https://www.google.com/ping?sitemap=' . urlencode($sitemapUrl),
            'bing' => 'https://www.bing.com/ping?sitemap=' . urlencode($sitemapUrl),
        ];

        foreach ($searchEngines as $name => $url) {
            try {
                \Http::timeout(5)->get($url);
            } catch (\Exception $e) {
                \Log::warning("Failed to ping {$name}: " . $e->getMessage());
            }
        }
    }
}