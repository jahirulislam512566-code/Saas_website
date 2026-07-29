@props([
    'icon' => null,
    'label' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'danger',
])

@php
    $dropdownId = 'dropdown-' . \Illuminate\Support\Str::random(6);
@endphp

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="sidebar-dropdown">
    {{-- Dropdown Toggle --}}
    <button 
        @click="open = !open" 
        class="flex items-center w-full px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ 
            $active 
                ? 'bg-slate-800 text-white' 
                : 'text-slate-300 hover:bg-slate-800 hover:text-white' 
        }}"
        :class="{ 'bg-slate-800 text-white': open }"
    >
        @if($icon)
            <i class="fas fa-{{ $icon }} w-5"></i>
        @endif
        
        <span class="{{ $icon ? 'ml-2' : '' }} flex-1 text-left">{{ $label ?? $slot }}</span>
        
        @if($badge)
            <span class="bg-{{ $badgeColor }}-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">
                {{ $badge }}
            </span>
        @endif
        
        <i class="fas fa-chevron-right text-xs transition-transform duration-200" 
           :class="{ 'rotate-90': open }"></i>
    </button>

    {{-- Dropdown Items --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="ml-4 mt-1 space-y-1"
         x-cloak>
        {{ $slot }}
    </div>
</div>