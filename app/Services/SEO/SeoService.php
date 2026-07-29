<?php

namespace App\Services\SEO;

use App\Services\Seo\Analyzers\KeywordAnalyzer;
use App\Services\Seo\Analyzers\ContentAnalyzer;
use App\Services\Seo\SitemapGenerator;
use Illuminate\Support\Facades\Cache;

class SeoService
{
    protected KeywordAnalyzer $keywordAnalyzer;
    protected ContentAnalyzer $contentAnalyzer;
    protected SitemapGenerator $sitemapGenerator;

    public function __construct(
        KeywordAnalyzer $keywordAnalyzer,
        ContentAnalyzer $contentAnalyzer,
        SitemapGenerator $sitemapGenerator
    ) {
        $this->keywordAnalyzer = $keywordAnalyzer;
        $this->contentAnalyzer = $contentAnalyzer;
        $this->sitemapGenerator = $sitemapGenerator;
    }

    /**
     * Analyze content for SEO.
     */
    public function analyzeContent(string $content, array $targetKeywords = []): array
    {
        return $this->contentAnalyzer->analyze($content, $targetKeywords);
    }

    /**
     * Generate meta tags.
     */
    public function generateMetaTags(array $data): string
    {
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $keywords = $data['keywords'] ?? '';
        $image = $data['image'] ?? null;
        $url = $data['url'] ?? url()->current();

        $tags = [];

        // Title
        $tags[] = "<title>" . e($title) . "</title>";

        // Meta description
        if ($description) {
            $tags[] = '<meta name="description" content="' . e($description) . '">';
        }

        // Meta keywords
        if ($keywords) {
            $tags[] = '<meta name="keywords" content="' . e($keywords) . '">';
        }

        // Open Graph
        $tags[] = '<meta property="og:title" content="' . e($title) . '">';
        $tags[] = '<meta property="og:url" content="' . e($url) . '">';
        $tags[] = '<meta property="og:type" content="website">';
        
        if ($description) {
            $tags[] = '<meta property="og:description" content="' . e($description) . '">';
        }
        
        if ($image) {
            $tags[] = '<meta property="og:image" content="' . e($image) . '">';
        }

        // Twitter Card
        $tags[] = '<meta name="twitter:card" content="summary_large_image">';
        $tags[] = '<meta name="twitter:title" content="' . e($title) . '">';
        
        if ($description) {
            $tags[] = '<meta name="twitter:description" content="' . e($description) . '">';
        }
        
        if ($image) {
            $tags[] = '<meta name="twitter:image" content="' . e($image) . '">';
        }

        // Canonical URL
        $tags[] = '<link rel="canonical" href="' . e($url) . '">';

        return implode("\n", $tags);
    }

    /**
     * Generate sitemap.
     */
    public function generateSitemap(string $name = 'sitemap'): void
    {
        $this->sitemapGenerator->generate($name);
    }

    /**
     * Get sitemap content.
     */
    public function getSitemap(string $name = 'sitemap'): ?string
    {
        return $this->sitemapGenerator->getContent($name);
    }

    /**
     * Analyze keywords.
     */
    public function analyzeKeywords(string $text): array
    {
        return $this->keywordAnalyzer->analyze($text);
    }

    /**
     * Get keyword density.
     */
    public function getKeywordDensity(string $text, string $keyword): float
    {
        return $this->keywordAnalyzer->getDensity($text, $keyword);
    }

    /**
     * Generate URL slug.
     */
    public function generateSlug(string $text): string
    {
        $slug = \Illuminate\Support\Str::slug($text);
        
        // Ensure slug is unique if needed
        // $slug = $this->ensureUniqueSlug($slug);

        return $slug;
    }

    /**
     * Generate breadcrumb schema.
     */
    public function generateBreadcrumbSchema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['label'],
                    'item' => $item['url'],
                ];
            }, $items, array_keys($items)),
        ];
    }

    /**
     * Generate article schema.
     */
    public function generateArticleSchema(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? '',
            ],
            'datePublished' => $data['published_at'] ?? now()->toISOString(),
            'dateModified' => $data['updated_at'] ?? now()->toISOString(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $data['url'] ?? url()->current(),
            ],
        ];
    }

    /**
     * Generate organization schema.
     */
    public function generateOrganizationSchema(): array
    {
        $company = company();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $company['name'] ?? config('app.name'),
            'url' => url('/'),
            'logo' => asset(company('logo')),
            'email' => $company['email'] ?? null,
            'telephone' => $company['phone'] ?? null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $company['address'] ?? null,
                'addressLocality' => $company['city'] ?? null,
                'addressRegion' => $company['state'] ?? null,
                'postalCode' => $company['postal_code'] ?? null,
                'addressCountry' => $company['country'] ?? null,
            ],
            'sameAs' => $company['social'] ?? [],
        ];
    }

    /**
     * Check content for SEO issues.
     */
    public function checkSeoIssues(string $content): array
    {
        $issues = [];

        // Check content length
        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount < 300) {
            $issues[] = 'Content is too short. Consider adding more content.';
        }

        // Check for missing headings
        if (!preg_match('/<h[1-6]/', $content)) {
            $issues[] = 'No headings found. Use H1, H2, H3 tags for structure.';
        }

        // Check for image alt text
        preg_match_all('/<img[^>]+>/i', $content, $images);
        foreach ($images[0] as $image) {
            if (!preg_match('/alt=[\'"][^\'"]*[\'"]/i', $image)) {
                $issues[] = 'Image missing alt text.';
                break;
            }
        }

        // Check for internal links
        if (!preg_match('/<a[^>]+href=["\']\/[^"\']*["\'][^>]*>/i', $content)) {
            $issues[] = 'No internal links found. Add links to other content.';
        }

        return $issues;
    }

    /**
     * Optimize title.
     */
    public function optimizeTitle(string $title, int $maxLength = 60): string
    {
        if (strlen($title) <= $maxLength) {
            return $title;
        }

        $title = substr($title, 0, $maxLength);
        $lastSpace = strrpos($title, ' ');
        
        return $lastSpace !== false ? substr($title, 0, $lastSpace) . '...' : $title . '...';
    }

    /**
     * Optimize description.
     */
    public function optimizeDescription(string $description, int $maxLength = 160): string
    {
        if (strlen($description) <= $maxLength) {
            return $description;
        }

        $description = substr($description, 0, $maxLength);
        $lastSpace = strrpos($description, ' ');
        
        return $lastSpace !== false ? substr($description, 0, $lastSpace) . '...' : $description . '...';
    }
}