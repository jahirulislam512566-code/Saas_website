{{-- resources/views/components/sections/hero.blade.php --}}
@props([
    'title' => 'We Build Digital Experiences That Drive Growth',
    'subtitle' => 'Strategy, design, and development for forward-thinking brands.',
    'image' => null,
    'video' => null,
    'primaryText' => 'Get Started',
    'primaryLink' => '#contact',
    'secondaryText' => 'View Our Work',
    'secondaryLink' => '#work',
    'badge' => null,
    'stats' => [],
    'animation' => true,
    'alignment' => 'left',
])

@php
    $alignClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
    $alignClass = $alignClasses[$alignment] ?? $alignClasses['left'];
    $statsAlignment = $alignment === 'center' ? 'justify-center' : 'justify-start';
@endphp

<section class="relative overflow-hidden pt-32 pb-20 md:pt-40 md:pb-28 bg-gradient-to-br from-[#f8f7ff] via-white to-[#faf8ff]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Content --}}
            <div class="{{ $alignClass }} {{ $alignment === 'center' ? 'lg:col-span-2' : '' }}">
                @if($badge)
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#6c5ce7]/10 text-[#6c5ce7] rounded-full text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-[#6c5ce7] rounded-full animate-pulse"></span>
                        {{ $badge }}
                    </div>
                @endif

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#0a0a0a] leading-tight">
                    {!! $title !!}
                </h1>
                
                <p class="text-lg md:text-xl text-[#4a4a4a] mt-6 max-w-2xl {{ $alignment === 'center' ? 'mx-auto' : '' }}">
                    {{ $subtitle }}
                </p>

                <div class="flex flex-wrap gap-4 mt-8 {{ $alignment === 'center' ? 'justify-center' : '' }}">
                    <a href="{{ $primaryLink }}" class="inline-flex items-center px-8 py-4 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30 hover:shadow-xl hover:-translate-y-0.5">
                        {{ $primaryText }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ $secondaryLink }}" class="inline-flex items-center px-8 py-4 bg-white border-2 border-gray-200 text-[#0a0a0a] font-medium rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                        {{ $secondaryText }}
                    </a>
                </div>

                {{-- Stats --}}
                @if(count($stats) > 0)
                    <div class="flex flex-wrap gap-8 mt-12 {{ $statsAlignment }}">
                        @foreach($stats as $stat)
                            <div>
                                <p class="text-2xl font-bold text-[#0a0a0a]">{{ $stat['value'] }}</p>
                                <p class="text-sm text-[#4a4a4a]">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Media --}}
            @if($image || $video)
                <div class="relative lg:col-span-1">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        @if($video)
                            <video class="w-full h-auto" autoplay muted loop playsinline>
                                <source src="{{ $video }}" type="video/mp4">
                            </video>
                        @elseif($image)
                            <img src="{{ $image }}" alt="Hero Image" class="w-full h-auto object-cover">
                        @endif
                        
                        {{-- Decorative elements --}}
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-[#6c5ce7]/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-[#a78bfa]/10 rounded-full blur-2xl"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Background decoration --}}
    <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-[#6c5ce7]/5 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-1/4 h-1/2 bg-gradient-to-tr from-[#a78bfa]/5 to-transparent pointer-events-none"></div>
</section>