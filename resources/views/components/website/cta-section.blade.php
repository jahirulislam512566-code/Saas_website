{{-- resources/views/components/website/cta-section.blade.php --}}
@props([
    'title' => 'Ready to Get Started?',
    'subtitle' => 'Join thousands of satisfied customers.',
    'cta_text' => 'Get Started',
    'cta_link' => '#',
    'variant' => 'default'
])

@php
    $gradientClass = $variant === 'dark' 
        ? 'from-indigo-800 to-purple-800' 
        : 'from-indigo-600 to-purple-600';
    $textColor = $variant === 'dark' ? 'text-indigo-200' : 'text-indigo-100';
@endphp

<section {{ $attributes->merge(['class' => "py-20 bg-gradient-to-r {$gradientClass}"]) }}>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white">{{ $title }}</h2>
        <p class="mt-4 text-lg {{ $textColor }}">{{ $subtitle }}</p>
        <div class="mt-8">
            @if(Route::has($cta_link) || filter_var($cta_link, FILTER_VALIDATE_URL))
                <a href="{{ $cta_link }}" class="inline-flex items-center px-8 py-3 text-base font-medium text-indigo-600 bg-white hover:bg-gray-50 rounded-lg shadow-lg transition">
                    {{ $cta_text }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @else
                <a href="#" class="inline-flex items-center px-8 py-3 text-base font-medium text-indigo-600 bg-white hover:bg-gray-50 rounded-lg shadow-lg transition">
                    {{ $cta_text }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @endif
        </div>
    </div>
</section>