@props([
    'title',
    'description',
    'icon',
    'color' => 'primary',
    'href' => '#'
])

@php
    $colors = [
        'primary' => 'bg-primary-50 text-primary-600',
        'success' => 'bg-green-50 text-green-600',
        'warning' => 'bg-yellow-50 text-yellow-600',
        'danger' => 'bg-red-50 text-red-600',
        'info' => 'bg-blue-50 text-blue-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'gray' => 'bg-gray-50 text-gray-600',
    ];
    
    $colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<a href="{{ $href }}" class="block bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start space-x-4">
        <div class="w-12 h-12 rounded-lg {{ $colorClass }} flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $icon }} text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        </div>
        <i class="fas fa-chevron-right text-gray-300 mt-2"></i>
    </div>
</a>