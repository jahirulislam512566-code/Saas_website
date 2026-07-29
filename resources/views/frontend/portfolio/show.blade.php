{{-- resources/views/frontend/portfolio/show.blade.php --}}
@props([
    'project' => null,
])

@php
    if (!$project) {
        $project = [
            'title' => 'Project Title',
            'category' => 'Category',
            'client' => 'Client Name',
            'description' => 'Project description goes here...',
            'challenge' => 'The challenge we faced...',
            'solution' => 'Our solution...',
            'results' => ['Result 1', 'Result 2', 'Result 3'],
            'technologies' => ['Tech 1', 'Tech 2', 'Tech 3'],
            'image' => null,
            'gallery' => [],
            'testimonial' => null,
        ];
    }
@endphp

@extends('layouts.app')

@section('title', $project['title'] . ' - AgencyPro')
@section('description', $project['description'])

@section('schema')
    <x-seo.schema type="Article" :data="[
        'headline' => $project['title'],
        'description' => $project['description'],
        'image' => $project['image'] ?? asset('images/portfolio/default.jpg'),
        'author' => 'AgencyPro',
        'datePublished' => now()->toDateString(),
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        :title="$project['title']"
        subtitle="Case Study"
        :breadcrumb="[
            ['label' => 'Portfolio', 'url' => route('portfolio.index')],
            ['label' => $project['title']]
        ]"
    >
        <x-slot:actions>
            <span class="inline-flex items-center px-4 py-2 bg-[#6c5ce7]/10 text-[#6c5ce7] text-sm font-medium rounded-xl">
                {{ $project['category'] }}
            </span>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Project Details --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                {{-- Featured Image --}}
                @if($project['image'])
                    <div class="rounded-2xl overflow-hidden shadow-xl mb-12">
                        <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="w-full h-auto">
                    </div>
                @endif

                {{-- Client & Technologies --}}
                <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <span class="text-sm text-[#4a4a4a]">Client</span>
                        <p class="text-xl font-bold text-[#0a0a0a]">{{ $project['client'] }}</p>
                    </div>
                    @if(!empty($project['technologies']))
                        <div>
                            <span class="text-sm text-[#4a4a4a]">Technologies</span>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($project['technologies'] as $tech)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="prose prose-lg max-w-none mb-12">
                    <h2>Project Overview</h2>
                    <p>{{ $project['description'] }}</p>
                </div>

                {{-- Challenge --}}
                @if(isset($project['challenge']))
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-[#0a0a0a] mb-4">The Challenge</h2>
                        <p class="text-[#4a4a4a] leading-relaxed">{{ $project['challenge'] }}</p>
                    </div>
                @endif

                {{-- Solution --}}
                @if(isset($project['solution']))
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-[#0a0a0a] mb-4">Our Solution</h2>
                        <p class="text-[#4a4a4a] leading-relaxed">{{ $project['solution'] }}</p>
                    </div>
                @endif

                {{-- Results --}}
                @if(!empty($project['results']))
                    <div class="mb-12 bg-gradient-to-br from-[#6c5ce7]/5 to-[#a78bfa]/5 rounded-2xl p-8 border border-[#6c5ce7]/10">
                        <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6">Key Results</h2>
                        <ul class="space-y-3">
                            @foreach($project['results'] as $result)
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#6c5ce7] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-[#4a4a4a]">{{ $result }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Gallery --}}
                @if(!empty($project['gallery']))
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6">Project Gallery</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($project['gallery'] as $image)
                                <div class="rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                    <img src="{{ $image }}" alt="Gallery image" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Testimonial --}}
                @if(isset($project['testimonial']))
                    <x-cards.testimonial-card 
                        :quote="$project['testimonial']['quote']"
                        :author="$project['testimonial']['author']"
                        :role="$project['testimonial']['role']"
                        :rating="$project['testimonial']['rating'] ?? 5"
                    />
                @endif

                {{-- Navigation --}}
                <div class="flex flex-wrap justify-between gap-4 pt-8 border-t border-gray-200 mt-12">
                    <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-2 text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors">
                        ← Back to Portfolio
                    </a>
                    <a href="#contact" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                        Start a Similar Project
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Projects --}}
    @if(!empty($project['relatedProjects']))
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Related Work</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Similar Projects</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($project['relatedProjects'] as $related)
                        <x-cards.portfolio-card 
                            :image="$related['image'] ?? null"
                            :title="$related['title']"
                            :category="$related['category']"
                            :tags="$related['tags'] ?? []"
                            :link="$related['link'] ?? '#'"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <x-sections.cta 
        title="Ready to Start Your Own Project?"
        subtitle="Let's Build Something Amazing"
        description="Contact us today and let's create a success story for your business."
        primary-text="Start Your Project"
        primary-link="#contact"
        secondary-text="Explore Our Services"
        secondary-link="{{ route('services.index') }}"
        variant="default"
    />
@endsection