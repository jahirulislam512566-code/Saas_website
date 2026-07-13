{{-- resources/views/website/blog/show.blade.php --}}
@extends('layouts.website')

@section('title', $post->title ?? 'Blog Post - SaaS Platform')

@section('content')
    <!-- Blog Post -->
    <section class="py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Link -->
            <a href="{{ route('website.blog.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to all posts
            </a>
            
            <!-- Post Content -->
            <article class="mt-8">
                @if($post->featured_image ?? false)
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-2xl">
                @endif
                
                <div class="mt-8">
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        @if($post->category ?? false)
                            <span class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-100 rounded-full">{{ $post->category->name }}</span>
                            <span>•</span>
                        @endif
                        <span>{{ $post->created_at->format('M d, Y') ?? now()->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $post->reading_time ?? '5' }} min read</span>
                    </div>
                    
                    <h1 class="mt-4 text-4xl font-bold text-gray-900">{{ $post->title ?? 'Blog Post Title' }}</h1>
                    
                    <div class="mt-6 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                            {{ $post->author->initials ?? 'JD' }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $post->author->name ?? 'Jane Doe' }}</p>
                            <p class="text-xs text-gray-500">{{ $post->author->role ?? 'Team Member' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 prose max-w-none">
                        {!! $post->content ?? '<p>Content goes here.</p>' !!}
                    </div>
                </div>
                
                <!-- Tags -->
                @if($post->tags ?? false)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-sm text-gray-500">Tags:</span>
                            @foreach($post->tags as $tag)
                                <a href="{{ route('website.blog.tag', $tag->slug) }}" class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full hover:bg-indigo-100 transition">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Navigation -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                    @if($previousPost ?? false)
                        <a href="{{ route('website.blog.show', $previousPost->slug) }}" class="text-indigo-600 hover:text-indigo-800 transition">
                            ← {{ $previousPost->title }}
                        </a>
                    @endif
                    @if($nextPost ?? false)
                        <a href="{{ route('website.blog.show', $nextPost->slug) }}" class="text-indigo-600 hover:text-indigo-800 transition">
                            {{ $nextPost->title }} →
                        </a>
                    @endif
                </div>
            </article>
        </div>
    </section>
    
    <!-- Related Posts -->
    @if($relatedPosts ?? false)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                        <x-website.blog-card :post="$related" compact="true" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection