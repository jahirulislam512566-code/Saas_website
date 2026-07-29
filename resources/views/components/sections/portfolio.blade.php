{{-- resources/views/components/sections/portfolio.blade.php --}}
@props([
    'title' => 'Our Portfolio',
    'subtitle' => 'Featured Projects',
    'description' => 'Explore our latest work and see how we help brands achieve their goals.',
    'projects' => [],
    'filter' => true,
    'columns' => 3,
    'showMore' => true,
])

@php
    $colClasses = [
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-2 lg:grid-cols-3',
        4 => 'md:grid-cols-2 lg:grid-cols-4',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[3];
    $categories = array_unique(array_column($projects, 'category'));
@endphp

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
        </div>

        {{-- Filter --}}
        @if($filter && count($categories) > 1)
            <div class="flex flex-wrap justify-center gap-2 mb-12" x-data="{ active: 'all' }">
                <button class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-300" 
                        x-bind:class="active === 'all' ? 'bg-[#6c5ce7] text-white' : 'bg-white text-[#4a4a4a] hover:bg-gray-100'"
                        x-on:click="active = 'all'">
                    All
                </button>
                @foreach($categories as $category)
                    <button class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-300"
                            x-bind:class="active === '{{ $category }}' ? 'bg-[#6c5ce7] text-white' : 'bg-white text-[#4a4a4a] hover:bg-gray-100'"
                            x-on:click="active = '{{ $category }}'">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Projects Grid --}}
        <div class="grid grid-cols-1 {{ $colClass }} gap-6">
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

        {{-- Show More --}}
        @if($showMore)
            <div class="text-center mt-12">
                <a href="#portfolio" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                    View All Projects
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>