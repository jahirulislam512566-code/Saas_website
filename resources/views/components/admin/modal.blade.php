@props([
    'id' => 'modal-' . uniqid(),
    'title' => null,
    'size' => 'md',
    'show' => false,
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        'full' => 'max-w-4xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div x-data="{ show: @json($show) }" 
     x-on:keydown.escape="show = false"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
>
    <div class="flex min-h-screen items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition-opacity ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50"
             @click="show = false">
        </div>
        
        <!-- Modal -->
        <div x-show="show"
             x-transition:enter="transition-all ease-in-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition-all ease-in-out duration-300 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative {{ $sizeClass }} w-full bg-white rounded-xl shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-4">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            @if(isset($footer))
                <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>