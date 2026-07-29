{{-- resources/views/components/sections/technologies.blade.php --}}
@props([
    'title' => 'Technologies We Use',
    'subtitle' => 'Our Tech Stack',
    'description' => 'We leverage modern technologies to build cutting-edge solutions.',
    'technologies' => [],
    'layout' => 'grid',
    'animation' => true,
])

@php
    $layoutClasses = [
        'grid' => 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6',
        'marquee' => 'overflow-hidden relative',
        'list' => 'flex flex-wrap gap-4',
    ];
    $layoutClass = $layoutClasses[$layout] ?? $layoutClasses['grid'];
@endphp

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
        </div>

        {{-- Technologies --}}
        @if($layout === 'marquee')
            <div class="relative">
                <div class="flex animate-marquee whitespace-nowrap" style="animation: marquee 30s linear infinite;">
                    @foreach($technologies as $tech)
                        <div class="mx-8 flex flex-col items-center gap-2">
                            <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center text-4xl">
                                {{ $tech['icon'] ?? '⚡' }}
                            </div>
                            <span class="text-sm font-medium text-[#0a0a0a]">{{ $tech['name'] }}</span>
                        </div>
                    @endforeach
                    {{-- Duplicate for seamless loop --}}
                    @foreach($technologies as $tech)
                        <div class="mx-8 flex flex-col items-center gap-2">
                            <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center text-4xl">
                                {{ $tech['icon'] ?? '⚡' }}
                            </div>
                            <span class="text-sm font-medium text-[#0a0a0a]">{{ $tech['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="{{ $layoutClass }}">
                @foreach($technologies as $tech)
                    <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 text-center group">
                        <div class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-300">
                            {{ $tech['icon'] ?? '⚡' }}
                        </div>
                        <span class="block text-sm font-medium text-[#0a0a0a]">{{ $tech['name'] }}</span>
                        @if(isset($tech['description']))
                            <span class="text-xs text-[#4a4a4a]">{{ $tech['description'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 30s linear infinite;
    }
</style>