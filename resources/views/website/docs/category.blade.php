{{-- resources/views/website/docs/category.blade.php --}}
@extends('layouts.website')

@section('title', $category['name'] . ' - Documentation')
@section('meta_description', $category['description'] ?? 'Browse documentation articles in this category.')

@section('content')
    {{-- Category Header --}}
    <section class="bg-gray-50 border-b border-gray-200 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl">
                <nav class="flex items-center text-sm text-gray-500 mb-4">
                    <a href="{{ route('docs.index') }}" class="hover:text-gray-700">Documentation</a>
                    <svg class="mx-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 font-medium">{{ $category['name'] }}</span>
                </nav>
                
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                    {{ $category['name'] }}
                </h1>
                @if(isset($category['description']))
                    <p class="mt-2 text-xl text-gray-600">{{ $category['description'] }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Category Content --}}
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    <x-website.docs-sidebar :activeCategory="$category['slug'] ?? null" />
                </aside>

                {{-- Articles --}}
                <main class="lg:col-span-3">
                    @if(isset($category['subcategories']) && count($category['subcategories']) > 0)
                        {{-- Subcategories --}}
                        @foreach($category['subcategories'] as $subcategory)
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $subcategory['name'] }}</h2>
                                <div class="space-y-3">
                                    @foreach($subcategory['articles'] ?? [] as $article)
                                        <x-website.doc-list-item 
                                            :title="$article['title']"
                                            :description="$article['description'] ?? ''"
                                            :category="$subcategory['name']"
                                            :link="route('docs.article', $article['slug'])"
                                            :icon="isset($article['icon']) ? $article['icon'] : null"
                                        />
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Articles List --}}
                        <div class="space-y-4">
                            @foreach($category['articles'] ?? [] as $article)
                                <x-website.doc-list-item 
                                    :title="$article['title']"
                                    :description="$article['description'] ?? ''"
                                    :category="$category['name']"
                                    :link="route('docs.article', $article['slug'])"
                                    :icon="isset($article['icon']) ? $article['icon'] : null"
                                />
                            @endforeach
                        </div>
                    @endif

                    {{-- Related Categories --}}
                    @if(isset($category['related_categories']) && count($category['related_categories']) > 0)
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Categories</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($category['related_categories'] as $related)
                                    <a href="{{ route('docs.category', $related['slug']) }}" class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                        <h4 class="font-medium text-gray-900 group-hover:text-primary-600">{{ $related['name'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $related['description'] ?? '' }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </section>
@endsection