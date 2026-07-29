{{-- resources/views/frontend/blog/show.blade.php --}}
@props([
    'post' => null,
])

@php
    if (!$post) {
        $post = [
            'title' => 'Blog Post Title',
            'content' => 'Blog post content goes here...',
            'author' => 'Author Name',
            'authorBio' => 'Author bio goes here...',
            'date' => date('M d, Y'),
            'category' => 'Category',
            'tags' => ['Tag 1', 'Tag 2', 'Tag 3'],
            'image' => null,
            'readingTime' => '5 min read',
            'relatedPosts' => [],
        ];
    }
@endphp

@extends('layouts.app')

@section('title', $post['title'] . ' - AgencyPro')
@section('description', strip_tags(str_limit($post['content'], 160)))

@section('schema')
    <x-seo.schema type="Article" :data="[
        'headline' => $post['title'],
        'description' => strip_tags(str_limit($post['content'], 160)),
        'image' => $post['image'] ?? asset('images/blog/default.jpg'),
        'author' => $post['author'],
        'datePublished' => $post['date'],
        'dateModified' => $post['date'],
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        :title="$post['title']"
        subtitle="Blog Post"
        :breadcrumb="[
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $post['category'], 'url' => route('blog.category', strtolower($post['category']))],
            ['label' => $post['title']]
        ]"
    >
        <x-slot:actions>
            <span class="inline-flex items-center px-4 py-2 bg-[#6c5ce7]/10 text-[#6c5ce7] text-sm font-medium rounded-xl">
                {{ $post['category'] }}
            </span>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Blog Content --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                {{-- Featured Image --}}
                @if($post['image'])
                    <div class="rounded-2xl overflow-hidden shadow-xl mb-8">
                        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="w-full h-auto">
                    </div>
                @endif

                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-4 text-sm text-[#4a4a4a] mb-6 pb-6 border-b border-gray-200">
                    <span>{{ $post['date'] }}</span>
                    <span>•</span>
                    <span>{{ $post['readingTime'] }}</span>
                    <span>•</span>
                    <span>By {{ $post['author'] }}</span>
                </div>

                {{-- Content --}}
                <div class="prose prose-lg max-w-none">
                    {!! $post['content'] !!}
                </div>

                {{-- Tags --}}
                @if(!empty($post['tags']))
                    <div class="flex flex-wrap gap-2 mt-8 pt-8 border-t border-gray-200">
                        @foreach($post['tags'] as $tag)
                            <a href="{{ route('blog.tag', strtolower(str_replace(' ', '-', $tag))) }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-[#6c5ce7] hover:text-white transition-colors no-underline">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Author Bio --}}
                <div class="bg-gradient-to-br from-[#f8f7ff] to-white rounded-2xl p-8 mt-12 border border-[#6c5ce7]/10">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                            {{ strtoupper(substr($post['author'], 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-[#0a0a0a]">{{ $post['author'] }}</h4>
                            <p class="text-sm text-[#4a4a4a]">{{ $post['authorBio'] ?? 'Digital expert with a passion for creating exceptional experiences.' }}</p>
                            <div class="flex gap-3 mt-2">
                                <a href="#" class="text-sm text-[#6c5ce7] hover:underline">Twitter</a>
                                <a href="#" class="text-sm text-[#6c5ce7] hover:underline">LinkedIn</a>
                                <a href="#" class="text-sm text-[#6c5ce7] hover:underline">Website</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Share Buttons --}}
                <div class="flex flex-wrap items-center justify-between gap-4 mt-8 pt-8 border-t border-gray-200">
                    <span class="text-sm font-medium text-[#0a0a0a]">Share this article:</span>
                    <div class="flex gap-2">
                        <button class="p-2 bg-gray-100 rounded-lg hover:bg-[#6c5ce7] hover:text-white transition-colors">🐦</button>
                        <button class="p-2 bg-gray-100 rounded-lg hover:bg-[#6c5ce7] hover:text-white transition-colors">🔗</button>
                        <button class="p-2 bg-gray-100 rounded-lg hover:bg-[#6c5ce7] hover:text-white transition-colors">📧</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Posts --}}
    @if(!empty($post['relatedPosts']))
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">You Might Also Like</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Related Articles</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($post['relatedPosts'] as $related)
                        <x-cards.blog-card 
                            :image="$related['image'] ?? null"
                            :title="$related['title']"
                            :excerpt="$related['excerpt'] ?? ''"
                            :author="$related['author'] ?? ''"
                            :date="$related['date'] ?? date('M d, Y')"
                            :category="$related['category'] ?? ''"
                            :reading-time="$related['readingTime'] ?? '5 min read'"
                            :tags="$related['tags'] ?? []"
                            :link="$related['link'] ?? '#'"
                            :featured="false"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <x-sections.cta 
        title="Enjoyed This Article?"
        subtitle="Stay Connected"
        description="Subscribe to our newsletter for more insights and updates from our team."
        primary-text="Subscribe Now"
        primary-link="#contact"
        secondary-text="Read More Articles"
        secondary-link="{{ route('blog.index') }}"
        variant="default"
    />
@endsection