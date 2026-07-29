{{-- resources/views/frontend/blog/tag.blade.php --}}
@props([
    'tag' => 'Design',
    'posts' => [],
])

@extends('layouts.app')

@section('title', '#' . $tag . ' - AgencyPro')
@section('description', 'Explore all articles tagged with ' . $tag . ' on the AgencyPro blog.')

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        :title="'#' . $tag"
        subtitle="Tag"
        :breadcrumb="[
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => '#' . $tag]
        ]"
    />

    {{-- Posts Grid --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Tag</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Articles Tagged with #{{ $tag }}</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">All articles related to {{ $tag }} from our expert team.</p>
            </div>

            @if(count($posts) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
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
                    <x-ui.pagination :current="1" :total="100" :per-page="9" url="{{ route('blog.tag', strtolower($tag)) }}" />
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">🏷️</div>
                    <h3 class="text-2xl font-bold text-[#0a0a0a]">No Posts Found</h3>
                    <p class="text-[#4a4a4a] mt-2">No articles tagged with #{{ $tag }} yet.</p>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                        View All Articles →
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection