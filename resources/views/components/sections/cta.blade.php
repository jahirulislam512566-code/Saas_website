{{-- resources/views/components/sections/cta.blade.php --}}
@props([
    'title' => 'Ready to Start Your Project?',
    'subtitle' => 'Let\'s Work Together',
    'description' => 'Contact us today and let\'s bring your vision to life.',
    'primaryText' => 'Get Started',
    'primaryLink' => '#contact',
    'secondaryText' => 'Learn More',
    'secondaryLink' => '#about',
    'variant' => 'default',
    'image' => null,
])

@php
    $variants = [
        'default' => 'bg-gradient-to-r from-[#6c5ce7] to-[#a78bfa] text-white',
        'dark' => 'bg-[#0a0a0a] text-white',
        'light' => 'bg-gray-50 text-[#0a0a0a]',
    ];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $textColor = $variant === 'light' ? 'text-[#0a0a0a]' : 'text-white';
    $descriptionColor = $variant === 'light' ? 'text-[#4a4a4a]' : 'text-white/80';
@endphp

<section class="py-20 {{ $variantClass }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <span class="text-sm font-semibold uppercase tracking-wider {{ $variant === 'light' ? 'text-[#6c5ce7]' : 'text-white/80' }}">
                {{ $subtitle }}
            </span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 {{ $textColor }}">{{ $title }}</h2>
            <p class="mt-4 text-lg {{ $descriptionColor }}">{{ $description }}</p>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a href="{{ $primaryLink }}" class="inline-flex items-center px-8 py-4 bg-white text-[#6c5ce7] font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg shadow-[#0a0a0a]/20">
                    {{ $primaryText }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="{{ $secondaryLink }}" class="inline-flex items-center px-8 py-4 border-2 {{ $variant === 'light' ? 'border-gray-300 text-[#0a0a0a] hover:border-[#6c5ce7] hover:text-[#6c5ce7]' : 'border-white/30 text-white hover:border-white' }} font-medium rounded-xl transition-all duration-300">
                    {{ $secondaryText }}
                </a>
            </div>
        </div>
    </div>
</section>