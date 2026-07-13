@props([
    'label' => null,
    'icon' => null,
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white',
])

@php
    $alignClasses = [
        'left' => 'left-0',
        'right' => 'right-0',
        'center' => 'left-1/2 transform -translate-x-1/2',
    ];
    
    $widthClasses = [
        '48' => 'w-48',
        '64' => 'w-64',
        '80' => 'w-80',
        '96' => 'w-96',
    ];
    
    $alignClass = $alignClasses[$align] ?? $alignClasses['right'];
    $widthClass = $widthClasses[$width] ?? $widthClasses['48'];
@endphp

<div x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" class="relative">
    <!-- Trigger -->
    <div @click="open = !open">
        @if($icon)
            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-{{ $icon }} mr-2"></i>
                @if($label) <span>{{ $label }}</span> @endif
                <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        @else
            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                {{ $label ?? 'Dropdown' }}
                <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif
    </div>
    
    <!-- Dropdown Content -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $widthClass }} rounded-md shadow-lg {{ $alignClass }}"
         style="display: none;"
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $slot }}
        </div>
    </div>
</div>