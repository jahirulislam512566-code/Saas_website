{{-- resources/views/components/sections/stats.blade.php --}}
@props([
    'title' => 'Our Impact',
    'subtitle' => 'By the Numbers',
    'description' => 'The results speak for themselves.',
    'stats' => [],
    'columns' => 4,
])

@php
    $colClasses = [
        2 => 'grid-cols-1 sm:grid-cols-2',
        3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[4];
@endphp

<section class="py-20 bg-[#0a0a0a] text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2">{{ $title }}</h2>
            <p class="mt-4 text-lg text-gray-400">{{ $description }}</p>
        </div>

        {{-- Stats --}}
        <div class="grid {{ $colClass }} gap-8">
            @foreach($stats as $stat)
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-[#6c5ce7]">{{ $stat['value'] }}</div>
                    <p class="mt-2 text-gray-300">{{ $stat['label'] }}</p>
                    @if(isset($stat['change']))
                        <span class="inline-block mt-1 text-sm font-medium {{ $stat['change'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $stat['change'] >= 0 ? '↑' : '↓' }} {{ abs($stat['change']) }}%
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>