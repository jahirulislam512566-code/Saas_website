{{-- resources/views/website/blog/category.blade.php --}}
@extends('layouts.website')

@section('title', $category->name ?? 'Category - SaaS Blog')

@section('content')
    <!-- Category Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <a href="{{ route('website.blog.index') }}" class="hover:text-indigo-600">Blog</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $category->name ?? 'Category' }}</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900">{{ $category->name ?? 'Category Name' }}</h1>
            @if($category->description ?? false)
                <p class="mt-4 text-xl text-gray-600 max-w-3xl">{{ $category->description }}</p>
            @endif
            <p class="mt-4 text-sm text-gray-500">{{ $posts->total() ?? 0 }} articles</p>
        </div>
    </section>
    
    <!-- Posts -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <x-website.blog-card :post="$post" />
                    @endforeach
                </div>
                
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No posts in this category yet.</p>
                    <a href="{{ route('website.blog.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">← Back to all posts</a>
                </div>
            @endif
        </div>
    </section>
@endsection