{{-- resources/views/website/portfolio/category.blade.php --}}
@extends('layouts.website')

@section('title', $category->name ?? 'Portfolio Category - SaaS Platform')

@section('content')
    <!-- Category Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <a href="{{ route('website.portfolio.index') }}" class="hover:text-indigo-600">Portfolio</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $category->name ?? 'Category' }}</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900">{{ $category->name ?? 'Category Name' }}</h1>
            @if($category->description ?? false)
                <p class="mt-4 text-xl text-gray-600 max-w-3xl">{{ $category->description }}</p>
            @endif
            <p class="mt-4 text-sm text-gray-500">{{ $projects->total() ?? 0 }} projects</p>
        </div>
    </section>
    
    <!-- Projects -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($projects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <x-website.portfolio-card :project="$project" />
                    @endforeach
                </div>
                
                <div class="mt-12">
                    {{ $projects->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No projects in this category yet.</p>
                    <a href="{{ route('website.portfolio.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">← Back to portfolio</a>
                </div>
            @endif
        </div>
    </section>
@endsection