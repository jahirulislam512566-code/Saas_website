{{-- resources/views/components/sections/services.blade.php --}}
@props([
    'title' => 'Our Services',
    'subtitle' => 'We deliver exceptional digital solutions',
    'description' => 'From strategy to execution, we provide comprehensive services to help your business thrive.',
    'services' => [],
    'columns' => 3,
    'background' => 'white',
])

@php
    $bgClasses = [
        'white' => 'bg-white',
        'gray' => 'bg-gray-50',
        'dark' => 'bg-[#0a0a0a] text-white',
        'gradient' => 'bg-gradient-to-br from-[#f8f7ff] to-white',
    ];
    $bgClass = $bgClasses[$background] ?? $bgClasses['white'];
    $textColor = $background === 'dark' ? 'text-white' : 'text-[#0a0a0a]';
    $textSecondary = $background === 'dark' ? 'text-gray-300' : 'text-[#4a4a4a]';
    $colClasses = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[3];
@endphp

<section class="py-20 {{ $bgClass }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 {{ $textColor }}">{{ $title }}</h2>
            <p class="mt-4 text-lg {{ $textSecondary }}">{{ $description }}</p>
        </div>

        {{-- Services Grid --}}
        <div class="grid {{ $colClass }} gap-6 md:gap-8">
            @foreach($services as $service)
                <x-cards.service-card 
                    :icon="$service['icon'] ?? '🚀'"
                    :title="$service['title']"
                    :description="$service['description']"
                    :features="$service['features'] ?? []"
                    :link="$service['link'] ?? '#'"
                    :color="$service['color'] ?? 'primary'"
                    :featured="$service['featured'] ?? false"
                />
            @endforeach
        </div>
    </div>
</section>