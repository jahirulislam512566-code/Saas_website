@props([
    'open' => false,
    'collapsible' => true,
    'width' => 'w-64',
    'background' => 'bg-gray-900',
    'textColor' => 'text-white',
])

@php
    $classes = trim("{$background} {$textColor} {$width} flex-shrink-0 min-h-screen");
@endphp

<div x-data="{ open: @json($open) }" 
     class="{{ $classes }}"
     {{ $attributes }}
>
    <div class="h-full flex flex-col overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800">
            <div class="flex items-center">
                {{ $brand ?? '' }}
            </div>
            
            @if($collapsible)
                <button @click="open = !open" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            @endif
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-1">
            {{ $slot }}
        </nav>
        
        <!-- Footer -->
        @if(isset($footer))
            <div class="border-t border-gray-800 p-4">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>