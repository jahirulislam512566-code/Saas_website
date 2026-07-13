@props([
    'variant' => 'info',
    'dismissible' => true,
    'duration' => 5000,
])

@php
    $variants = [
        'info' => 'bg-blue-50 border-blue-400 text-blue-800',
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
        'danger' => 'bg-red-50 border-red-400 text-red-800',
    ];
    
    $iconClasses = [
        'info' => 'fa-info-circle text-blue-400',
        'success' => 'fa-check-circle text-green-400',
        'warning' => 'fa-exclamation-triangle text-yellow-400',
        'danger' => 'fa-times-circle text-red-400',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['info'];
    $iconClass = $iconClasses[$variant] ?? $iconClasses['info'];
@endphp

<div x-data="{ show: true }" 
     x-init="setTimeout(() => { show = false }, {{ $duration }})"
     x-show="show"
     x-transition:enter="transition transform ease-out duration-300"
     x-transition:enter-start="translate-x-8 opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition transform ease-in duration-200"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-8 opacity-0"
     class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto overflow-hidden"
>
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas {{ $iconClass }}"></i>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">{{ $title ?? '' }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ $slot }}</p>
            </div>
            
            @if($dismissible)
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>