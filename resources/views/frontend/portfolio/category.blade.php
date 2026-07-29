{{-- resources/views/frontend/portfolio/category.blade.php --}}
@props([
    'category' => 'Web Development',
    'projects' => [],
])

@extends('layouts.app')

@section('title', $category . ' Projects - AgencyPro')
@section('description', 'Explore our ' . $category . ' projects. See how we\'ve helped businesses achieve their digital goals.')

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        :title="$category . ' Projects'"
        subtitle="Portfolio Category"
        :breadcrumb="[
            ['label' => 'Portfolio', 'url' => route('portfolio.index')],
            ['label' => $category]
        ]"
    />

    {{-- Projects Grid --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Category</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $category }} Projects</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">Discover our {{ $category }} projects and see how we've helped businesses succeed.</p>
            </div>

            @if(count($projects) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($projects as $project)
                        <x-cards.portfolio-card 
                            :image="$project['image'] ?? null"
                            :title="$project['title']"
                            :category="$project['category']"
                            :tags="$project['tags'] ?? []"
                            :link="$project['link'] ?? '#'"
                            :lightbox="$project['lightbox'] ?? false"
                        />
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">📂</div>
                    <h3 class="text-2xl font-bold text-[#0a0a0a]">No Projects Found</h3>
                    <p class="text-[#4a4a4a] mt-2">No projects available in this category yet.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <x-sections.cta 
        title="Have a Project in Mind?"
        subtitle="Let's Build It Together"
        description="Contact us today and let's discuss how we can help you achieve your goals."
        primary-text="Start Your Project"
        primary-link="#contact"
        secondary-text="View All Projects"
        secondary-link="{{ route('portfolio.index') }}"
        variant="default"
    />
@endsection