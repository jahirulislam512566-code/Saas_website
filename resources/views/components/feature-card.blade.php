@props([
    'icon' => '🎨',
    'title' => 'Feature Title',
    'description' => 'Feature description goes here.',
    'color' => 'indigo',
    'popular' => false,
    'stats' => null,
])

<div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-2xl border border-gray-100 hover:border-transparent p-6 transition-all duration-300 hover:-translate-y-2 {{ $attributes->get('class') }}">
    {{-- Popular Badge --}}
    @if($popular)
        <div class="absolute -top-2 -right-2">
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold rounded-full shadow-lg">
                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                Popular
            </span>
        </div>
    @endif

    {{-- Icon --}}
    <div class="w-14 h-14 bg-{{ $color }}-100 rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:shadow-lg transition-all duration-300">
        {{ $icon }}
    </div>

    {{-- Title --}}
    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-{{ $color }}-600 transition-colors">
        {{ $title }}
    </h3>

    {{-- Description --}}
    <p class="text-gray-600 leading-relaxed text-sm">
        {{ $description }}
    </p>

    {{-- Stats --}}
    @if($stats)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <span class="text-xs font-medium text-gray-400">{{ $stats }}</span>
        </div>
    @endif

    {{-- Learn More --}}
    <div class="mt-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
        <a href="#" class="inline-flex items-center gap-1 text-sm font-medium text-{{ $color }}-600 hover:text-{{ $color }}-700">
            Learn More
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    {{-- Hover Gradient Overlay --}}
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-{{ $color }}-500/5 to-{{ $color }}-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
</div>