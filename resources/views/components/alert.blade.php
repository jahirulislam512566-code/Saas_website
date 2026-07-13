@props([
    'variant' => 'info',
    'dismissible' => false,
    'icon' => null,
])

@php
    $variants = [
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-400',
            'text' => 'text-blue-800',
            'icon' => 'fa-info-circle',
            'iconColor' => 'text-blue-400',
        ],
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-400',
            'text' => 'text-green-800',
            'icon' => 'fa-check-circle',
            'iconColor' => 'text-green-400',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-400',
            'text' => 'text-yellow-800',
            'icon' => 'fa-exclamation-triangle',
            'iconColor' => 'text-yellow-400',
        ],
        'danger' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-400',
            'text' => 'text-red-800',
            'icon' => 'fa-times-circle',
            'iconColor' => 'text-red-400',
        ],
    ];
    
    $config = $variants[$variant] ?? $variants['info'];
    $iconClass = $icon ?? $config['icon'];
    $bgClass = $config['bg'];
    $borderClass = $config['border'];
    $textClass = $config['text'];
    $iconColorClass = $config['iconColor'];
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-in duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     class="rounded-md {{ $bgClass }} border-l-4 {{ $borderClass }} p-4 {{ $attributes->get('class') }}"
     {{ $attributes->except(['class']) }}
>
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas {{ $iconClass }} {{ $iconColorClass }}"></i>
        </div>
        <div class="ml-3 flex-1">
            <div class="text-sm {{ $textClass }}">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button"
                            @click="show = false"
                            class="inline-flex rounded-md p-1.5 {{ $bgClass }} {{ $textClass }} hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ $variant }}-50 focus:ring-{{ $variant }}-600"
                    >
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>