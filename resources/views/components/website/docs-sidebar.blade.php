{{-- resources/views/components/website/docs-sidebar.blade.php --}}
@props(['activeCategory' => null])

@php
    $categories = [
        [
            'name' => 'Getting Started',
            'slug' => 'getting-started',
            'icon' => 'fa-rocket',
            'items' => [
                ['title' => 'Quick Start Guide', 'slug' => 'quick-start'],
                ['title' => 'Installation', 'slug' => 'installation'],
                ['title' => 'Authentication', 'slug' => 'authentication'],
            ]
        ],
        [
            'name' => 'Guides',
            'slug' => 'guides',
            'icon' => 'fa-book',
            'items' => [
                ['title' => 'Building Your First App', 'slug' => 'building-first-app'],
                ['title' => 'Advanced Configuration', 'slug' => 'advanced-config'],
                ['title' => 'Performance Optimization', 'slug' => 'performance-optimization'],
            ]
        ],
        [
            'name' => 'API Reference',
            'slug' => 'api-reference',
            'icon' => 'fa-code',
            'items' => [
                ['title' => 'Authentication', 'slug' => 'api-auth'],
                ['title' => 'Users', 'slug' => 'api-users'],
                ['title' => 'Products', 'slug' => 'api-products'],
            ]
        ],
        [
            'name' => 'FAQ',
            'slug' => 'faq',
            'icon' => 'fa-question-circle',
            'items' => [
                ['title' => 'General Questions', 'slug' => 'faq-general'],
                ['title' => 'Pricing', 'slug' => 'faq-pricing'],
                ['title' => 'Technical', 'slug' => 'faq-technical'],
            ]
        ]
    ];
@endphp

<nav class="space-y-8" aria-label="Documentation navigation">
    @foreach($categories as $category)
        <div>
            <div class="flex items-center gap-2">
                <i class="fas {{ $category['icon'] }} text-gray-400 text-sm"></i>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    {{ $category['name'] }}
                </h3>
            </div>
            <ul class="mt-2 space-y-1">
                @foreach($category['items'] as $item)
                    <li>
                        <a href="{{ route('docs.article', $item['slug']) }}" 
                           class="block px-3 py-2 text-sm rounded-lg transition-colors
                                  {{ request()->routeIs('docs.article', $item['slug']) ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>