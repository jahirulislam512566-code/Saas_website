{{-- resources/views/components/ui/dropdown.blade.php --}}
@props([
    'trigger' => null,
    'align' => 'right',
    'width' => 'auto',
])

@php
    $alignClasses = [
        'left' => 'left-0',
        'right' => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
    ];
    $widthClasses = [
        'auto' => 'w-auto',
        'sm' => 'w-48',
        'md' => 'w-56',
        'lg' => 'w-64',
        'xl' => 'w-80',
        'full' => 'w-full',
    ];
    $alignClass = $alignClasses[$align] ?? $alignClasses['right'];
    $widthClass = $widthClasses[$width] ?? $widthClasses['auto'];
@endphp

<div class="relative inline-block" x-data="{ open: false }" x-on:click.outside="open = false">
    <div x-on:click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $alignClass }} {{ $widthClass }} bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 overflow-hidden">
        {{ $slot }}
    </div>
</div>

{{-- Dropdown Item --}}
@props(['href' => null, 'active' => false])
@php
    $activeClass = $active ? 'bg-[#6c5ce7]/10 text-[#6c5ce7]' : 'hover:bg-gray-50 text-[#0a0a0a]';
@endphp
@if($href)
    <a href="{{ $href }}" class="flex items-center px-4 py-2.5 text-sm {{ $activeClass }} transition-colors no-underline" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button class="flex items-center w-full px-4 py-2.5 text-sm {{ $activeClass }} transition-colors bg-transparent border-none cursor-pointer text-left" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif