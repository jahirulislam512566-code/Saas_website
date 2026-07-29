{{-- resources/views/website/docs/search.blade.php --}}
@extends('layouts.website')

@section('title', 'Search Results - Documentation')
@section('meta_description', 'Search results for documentation articles.')

@section('content')
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900">Search Results</h1>
                
                @if(!empty($query))
                    <p class="mt-2 text-gray-600">
                        Showing results for: <span class="font-medium text-gray-900">"{{ $query }}"</span>
                    </p>
                @endif
                
                <div class="mt-8">
                    @if(count($results) > 0)
                        <div class="space-y-4">
                            @foreach($results as $result)
                                <x-website.doc-list-item 
                                    :title="$result['title']"
                                    :description="$result['description'] ?? ''"
                                    :category="$result['category'] ?? ''"
                                    :link="route('docs.article', $result['slug'])"
                                />
                            @endforeach
                        </div>
                    @elseif(!empty($query))
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No results found</h3>
                            <p class="mt-2 text-gray-600">Try adjusting your search terms or browse our categories.</p>
                            <a href="{{ route('docs.index') }}" class="mt-4 inline-block text-primary-600 hover:text-primary-700">
                                Back to Documentation →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-600">Enter a search term to find documentation articles.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection