{{-- resources/views/components/website/doc-card.blade.php --}}
@props(['title', 'description', 'icon', 'link', 'color' => 'blue'])

@php
    $colors = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'red' => 'bg-red-50 text-red-600',
        'teal' => 'bg-teal-50 text-teal-600',
    ];
    $colorClass = $colors[$color] ?? $colors['blue'];
@endphp

<a href="{{ $link }}" class="doc-card block p-6 bg-white rounded-xl border border-gray-200 hover:border-primary-300 transition-all duration-200">
    <div class="flex items-start">
        <div class="flex-shrink-0 w-12 h-12 {{ $colorClass }} rounded-xl flex items-center justify-center mr-4">
            <i class="fas {{ $icon }} text-xl"></i>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            <span class="mt-2 inline-flex items-center text-sm text-primary-600 font-medium">
                Learn more
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </div>
</a>