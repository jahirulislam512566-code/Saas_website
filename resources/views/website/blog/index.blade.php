{{-- resources/views/website/blog/index.blade.php --}}
@extends('layouts.website')

@section('title', 'Blog - SaaS Platform')

@section('content')
    <!-- Blog Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900">SaaS Blog</h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Insights, strategies, and product updates from our team.
            </p>
            
            <!-- Categories -->
            <div class="mt-8 flex flex-wrap justify-center gap-2">
                <a href="#" class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">All</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Product</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Engineering</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Design</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Growth</a>
            </div>
        </div>
    </section>
    
    <!-- Featured Post -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($featuredPost ?? false)
                <x-website.featured-post :post="$featuredPost" />
            @endif
        </div>
    </section>
    
    <!-- Blog Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts ?? [] as $post)
                    <x-website.blog-card :post="$post" />
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No blog posts found.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if(isset($posts) && method_exists($posts, 'links'))
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
    
    <!-- Newsletter -->
    <section class="py-16 bg-indigo-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white">Subscribe to Our Newsletter</h2>
            <p class="mt-4 text-indigo-100">Get the latest insights delivered to your inbox.</p>
            <form class="mt-8 flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                <button type="submit" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-50 transition">Subscribe</button>
            </form>
        </div>
    </section>
@endsection