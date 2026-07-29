{{-- resources/views/components/seo/schema.blade.php --}}
@props([
    'type' => 'Organization',
    'data' => [],
])

@php
    $schema = [];
    
    if ($type === 'Organization') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $data['name'] ?? 'AgencyPro',
            'url' => $data['url'] ?? url('/'),
            'logo' => $data['logo'] ?? asset('images/logo.png'),
            'description' => $data['description'] ?? 'Digital agency specializing in web design and development.',
            'address' => $data['address'] ?? null,
            'contactPoint' => $data['contactPoint'] ?? null,
            'sameAs' => $data['social'] ?? [],
        ];
    } elseif ($type === 'WebSite') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $data['name'] ?? 'AgencyPro',
            'url' => $data['url'] ?? url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $data['searchUrl'] ?? url('/search?q={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
    } elseif ($type === 'Article') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['headline'] ?? 'Article Title',
            'description' => $data['description'] ?? 'Article description.',
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? 'Author Name',
            ],
            'datePublished' => $data['datePublished'] ?? date('Y-m-d'),
            'dateModified' => $data['dateModified'] ?? date('Y-m-d'),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $data['url'] ?? url('/'),
            ],
        ];
    } elseif ($type === 'BreadcrumbList') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];
        foreach ($data['items'] ?? [] as $index => $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }
    } elseif ($type === 'FAQPage') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [],
        ];
        foreach ($data['faqs'] ?? [] as $faq) {
            $schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }
    }
@endphp

@if(!empty($schema))
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endif