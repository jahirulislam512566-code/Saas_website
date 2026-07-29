{{-- resources/views/frontend/blog/search.blade.php --}}
@props([
    'query' => '',
    'results' => [],
])

@extends('layouts.app')

@section('title', 'Search Results - AgencyPro')
@section('description', 'Search results for "' . $query . '" on the AgencyPro blog.')

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        :title="'Search Results'"
        subtitle="Search"
        :breadcrumb="[
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => 'Search']
        ]"
    />

    {{-- Search Results --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto mb-12">
                <form action="{{ route('blog.search') }}" method="GET" class="flex gap-3">
                    <input type="text" name="q" placeholder="Search articles..." value="{{ $query }}"
                           class="flex-1 px-5 py-3.5 text-lg rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-[#6c5ce7]/20 transition-all outline-none">
                    <button type="submit" class="px-8 py-3.5 bg-[#6c5ce7] text-white text-lg font-medium rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                        Search
                    </button>
                </form>
            </div>

            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-2xl font-bold text-[#0a0a0a]">
                    {{ count($results) }} {{ count($results) === 1 ? 'result' : 'results' }} for "{{ $query }}"
                </h2>
            </div>

            @if(count($results) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($results as $post)
                        <x-cards.blog-card 
                            :image="$post['image'] ?? null"
                            :title="$post['title']"
                            :excerpt="$post['excerpt'] ?? ''"
                            :author="$post['author'] ?? ''"
                            :date="$post['date'] ?? date('M d, Y')"
                            :category="$post['category'] ?? ''"
                            :reading-time="$post['readingTime'] ?? '5 min read'"
                            :tags="$post['tags'] ?? []"
                            :link="$post['link'] ?? '#'"
                            :featured="$post['featured'] ?? false"
                        />
                    @endforeach
                </div>
                <div class="mt-12">
                    <x-ui.pagination :current="1" :total="100" :per-page="9" url="{{ route('blog.search') }}" />
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-2xl font-bold text-[#0a0a0a]">No Results Found</h3>
                    <p class="text-[#4a4a4a] mt-2">We couldn't find any articles matching "{{ $query }}".</p>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                        Browse All Articles →
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection