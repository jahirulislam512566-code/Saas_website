@props([
    'title',
    'value',
    'icon',
    'color' => 'indigo',
    'trend' => null,
    'trendValue' => null,
])

@php
    $colors = [
        'indigo' => 'border-indigo-500 bg-indigo-50 text-indigo-600',
        'green' => 'border-green-500 bg-green-50 text-green-600',
        'blue' => 'border-blue-500 bg-blue-50 text-blue-600',
        'yellow' => 'border-yellow-500 bg-yellow-50 text-yellow-600',
        'red' => 'border-red-500 bg-red-50 text-red-600',
        'purple' => 'border-purple-500 bg-purple-50 text-purple-600',
        'pink' => 'border-pink-500 bg-pink-50 text-pink-600',
    ];
    $colorClass = $colors[$color] ?? $colors['indigo'];
    
    $trendColor = $trend === 'up' ? 'text-green-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-500');
    $trendIcon = $trend === 'up' ? 'fa-arrow-up' : ($trend === 'down' ? 'fa-arrow-down' : 'fa-minus');
@endphp

<div class="bg-white rounded-xl shadow-sm border-l-4 {{ $colorClass }} p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
            @if($trendValue)
                <div class="flex items-center mt-1">
                    <span class="text-xs font-medium {{ $trendColor }}">
                        <i class="fas {{ $trendIcon }} mr-1"></i>
                        {{ $trendValue }}
                    </span>
                    <span class="text-xs text-gray-500 ml-1">vs last month</span>
                </div>
            @endif
        </div>
        <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center">
            <i class="fas {{ $icon }} text-xl {{ str_replace('border-', 'text-', $colorClass) }}"></i>
        </div>
    </div>
</div>