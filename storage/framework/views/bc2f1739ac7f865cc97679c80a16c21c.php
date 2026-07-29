
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'Organization',
    'data' => [],
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'type' => 'Organization',
    'data' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($schema)): ?>
    <script type="application/ld+json">
        <?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>

    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/components/seo/schema.blade.php ENDPATH**/ ?>