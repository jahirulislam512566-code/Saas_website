<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SeoHelper
{
    /**
     * SEO data
     */
    protected static $data = [];

    /**
     * Get SEO data
     */
    public static function get($key = null)
    {
        $defaults = self::getDefaults();

        if ($key) {
            return self::$data[$key] ?? $defaults[$key] ?? null;
        }

        return array_merge($defaults, self::$data);
    }

    /**
     * Set SEO data
     */
    public static function set($data): void
    {
        self::$data = array_merge(self::$data, $data);
    }

    /**
     * Get default SEO data
     */
    protected static function getDefaults(): array
    {
        $company = CompanyHelper::get();

        return [
            'title' => config('app.name', 'My App'),
            'description' => $company['description'] ?? 'Welcome to our application',
            'keywords' => $company['keywords'] ?? 'app, web, software',
            'og_title' => null,
            'og_description' => null,
            'og_image' => null,
            'og_url' => url()->current(),
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => null,
            'twitter_description' => null,
            'twitter_image' => null,
            'robots' => 'index, follow',
            'canonical' => url()->current(),
            'schema' => null,
        ];
    }

    /**
     * Generate meta tags
     */
    public static function generate(): string
    {
        $data = self::get();
        $html = '';

        // Title
        $title = $data['title'];
        if ($data['og_title']) {
            $title = $data['og_title'];
        }
        $html .= '<title>' . e($title) . '</title>' . "\n";

        // Meta description
        if ($data['description']) {
            $description = $data['og_description'] ?? $data['description'];
            $html .= '<meta name="description" content="' . e($description) . '">' . "\n";
        }

        // Meta keywords
        if ($data['keywords']) {
            $html .= '<meta name="keywords" content="' . e($data['keywords']) . '">' . "\n";
        }

        // Robots
        if ($data['robots']) {
            $html .= '<meta name="robots" content="' . e($data['robots']) . '">' . "\n";
        }

        // Canonical URL
        if ($data['canonical']) {
            $html .= '<link rel="canonical" href="' . e($data['canonical']) . '">' . "\n";
        }

        // Open Graph
        $html .= '<meta property="og:title" content="' . e($data['og_title'] ?? $data['title']) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . e($data['og_description'] ?? $data['description']) . '">' . "\n";
        $html .= '<meta property="og:url" content="' . e($data['og_url'] ?? url()->current()) . '">' . "\n";
        $html .= '<meta property="og:type" content="' . e($data['og_type'] ?? 'website') . '">' . "\n";

        if ($data['og_image']) {
            $html .= '<meta property="og:image" content="' . e($data['og_image']) . '">' . "\n";
            $html .= '<meta property="og:image:width" content="1200">' . "\n";
            $html .= '<meta property="og:image:height" content="630">' . "\n";
        }

        // Twitter Card
        $html .= '<meta name="twitter:card" content="' . e($data['twitter_card'] ?? 'summary_large_image') . '">' . "\n";
        $html .= '<meta name="twitter:title" content="' . e($data['twitter_title'] ?? $data['og_title'] ?? $data['title']) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . e($data['twitter_description'] ?? $data['og_description'] ?? $data['description']) . '">' . "\n";

        if ($data['twitter_image']) {
            $html .= '<meta name="twitter:image" content="' . e($data['twitter_image']) . '">' . "\n";
        }

        // Schema markup
        if ($data['schema']) {
            $html .= '<script type="application/ld+json">' . json_encode($data['schema']) . '</script>' . "\n";
        }

        return $html;
    }

    /**
     * Generate JSON-LD schema
     */
    public static function schema($type, $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $type,
        ];

        return array_merge($schema, $data);
    }

    /**
     * Generate Organization schema
     */
    public static function organizationSchema(): array
    {
        $company = CompanyHelper::get();

        return self::schema('Organization', [
            'name' => $company['name'] ?? config('app.name'),
            'url' => url('/'),
            'logo' => asset(CompanyHelper::logo()),
            'email' => $company['email'] ?? null,
            'telephone' => $company['phone'] ?? null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $company['address'] ?? null,
            ],
            'sameAs' => array_values(array_filter(CompanyHelper::social() ?? [])),
        ]);
    }

    /**
     * Generate Breadcrumb schema
     */
    public static function breadcrumbSchema($items): array
    {
        $schema = self::schema('BreadcrumbList', [
            'itemListElement' => [],
        ]);

        $position = 1;
        foreach ($items as $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['label'],
                'item' => $item['url'],
            ];
        }

        return $schema;
    }

    /**
     * Generate Article schema
     */
    public static function articleSchema($data): array
    {
        return self::schema('Article', [
            'headline' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? '',
            ],
            'datePublished' => $data['published_at'] ?? now()->toISOString(),
            'dateModified' => $data['updated_at'] ?? now()->toISOString(),
        ]);
    }
}