{{-- resources/views/components/sections/features.blade.php --}}
@props([
    'title' => 'Why Choose Us',
    'subtitle' => 'We deliver results that matter',
    'description' => 'Our approach combines creativity, strategy, and technology to drive real business growth.',
    'features' => [],
    'layout' => 'grid',
    'image' => null,
    'imagePosition' => 'right',
])

@php
    $layoutClasses = [
        'grid' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8',
        'list' => 'space-y-6',
        'image' => 'grid grid-cols-1 lg:grid-cols-2 gap-12 items-center',
    ];
    $layoutClass = $layoutClasses[$layout] ?? $layoutClasses['grid'];
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if($layout !== 'image')
            {{-- Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
            </div>
        @endif

        <div class="{{ $layoutClass }}">
            @if($layout === 'image' && $image)
                <div class="{{ $imagePosition === 'left' ? 'order-1' : 'order-2' }}">
                    <img src="{{ $image }}" alt="Features" class="rounded-2xl shadow-xl w-full h-auto">
                </div>
                <div class="{{ $imagePosition === 'left' ? 'order-2' : 'order-1' }}">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
                    <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
                    <div class="mt-8 space-y-6">
                        @foreach($features as $feature)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-2xl">
                                    {{ $feature['icon'] ?? '✓' }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-[#0a0a0a]">{{ $feature['title'] }}</h4>
                                    <p class="text-sm text-[#4a4a4a]">{{ $feature['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                @foreach($features as $feature)
                    <div class="p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition-all duration-300 group">
                        <div class="w-14 h-14 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-3xl mb-4 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                            {{ $feature['icon'] ?? '🎯' }}
                        </div>
                        <h4 class="text-xl font-bold text-[#0a0a0a] mb-2">{{ $feature['title'] }}</h4>
                        <p class="text-[#4a4a4a]">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>