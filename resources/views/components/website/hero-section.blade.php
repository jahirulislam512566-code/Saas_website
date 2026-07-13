{{-- resources/views/components/website/hero-section.blade.php --}}
@props([
    'title' => 'Build Better Products',
    'subtitle' => 'Streamline your workflow with our powerful SaaS platform.',
    'cta_text' => 'Get Started',
    'cta_link' => '#',
    'secondary_text' => 'Learn More',
    'secondary_link' => '#',
    'size' => 'large',
    'badge' => null,
    'stats' => null,
])

@php
    $paddingClass = $size === 'small' ? 'py-12 lg:py-16' : 'py-20 lg:py-28';
    $titleClass = $size === 'small' ? 'text-3xl md:text-4xl lg:text-5xl' : 'text-4xl md:text-5xl lg:text-6xl';
@endphp

<section {{ $attributes->merge(['class' => "relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50 {$paddingClass}"]) }}>
    <div class="absolute inset-0 bg-grid-slate-100 [mask-image:radial-gradient(ellipse_at_center,white,transparent)]"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto">
            @if($badge)
                <div class="inline-block mb-4">
                    {{ $badge }}
                </div>
            @endif
            
            <h1 class="{{ $titleClass }} font-extrabold tracking-tight text-gray-900 leading-tight">
                {{ $title }}
            </h1>
            
            <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $subtitle }}
            </p>
            
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                @if(Route::has($cta_link) || filter_var($cta_link, FILTER_VALIDATE_URL))
                    <a href="{{ $cta_link }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow transition-all">
                        {{ $cta_text }}
                    </a>
                @endif
                
                @if($secondary_text && $secondary_link)
                    <a href="{{ $secondary_link }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm hover:shadow transition-all">
                        {{ $secondary_text }}
                    </a>
                @endif
            </div>
            
            @if($stats)
                <div class="mt-12">
                    {{ $stats }}
                </div>
            @endif
        </div>
    </div>
</section>