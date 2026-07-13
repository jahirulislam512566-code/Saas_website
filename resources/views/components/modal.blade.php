@props([
    'id' => 'modal-' . uniqid(),
    'title' => null,
    'size' => 'md',
    'closeable' => true,
    'show' => false,
    'centered' => true,
    'header' => null,
    'footer' => null,
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        'full' => 'max-w-full mx-4',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $centerClass = $centered ? 'items-center' : 'items-start';
@endphp

<div x-data="{ show: @json($show) }" 
     x-init="() => { if (show) document.body.style.overflow = 'hidden'; }"
     x-on:keydown.escape="show = false; document.body.style.overflow = ''"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
>
    <div class="flex min-h-screen {{ $centerClass }} justify-center p-4">
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition-opacity ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50"
             @click="show = false; document.body.style.overflow = ''"
        ></div>
        
        <!-- Modal -->
        <div x-show="show"
             x-transition:enter="transition-all ease-in-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition-all ease-in-out duration-300 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative {{ $sizeClass }} w-full"
        >
            <div class="bg-white rounded-lg shadow-xl w-full">
                @if($closeable)
                    <button type="button"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors"
                            @click="show = false; document.body.style.overflow = ''"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
                
                @if($header)
                    <div class="border-b border-gray-200 px-6 py-4">
                        {{ $header }}
                    </div>
                @elseif($title)
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    </div>
                @endif
                
                <div class="px-6 py-4">
                    {{ $slot }}
                </div>
                
                @if($footer)
                    <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-lg">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>