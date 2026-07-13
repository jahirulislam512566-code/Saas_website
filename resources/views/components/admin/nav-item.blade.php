{{-- resources/views/components/admin/nav-item.blade.php --}}
@props([
    'active' => false,
    'href' => '#',
    'icon' => '',
    'badge' => null,
    'badgeColor' => 'blue'
])

@php
    $isActive = $active || request()->routeIs($active);
    $badgeColors = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'red' => 'bg-red-500',
        'purple' => 'bg-purple-500',
        'gray' => 'bg-gray-500',
        'indigo' => 'bg-indigo-500',
    ];
    $badgeClass = $badgeColors[$badgeColor] ?? 'bg-blue-500';
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group relative ' . ($isActive ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5')]) }}
   @click="if (!$store.sidebar?.isDesktop) $store.sidebar?.closeSidebar()"
   @if($isActive) aria-current="page" @endif>
    
    <i class="fas {{ $icon }} w-5 text-center text-base transition-colors {{ $isActive ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white' }}"></i>
    
    <span x-show="!$store.sidebar?.isCollapsed || $store.sidebar?.isHovering" 
          x-cloak
          class="flex-1 truncate">
        {{ $slot }}
    </span>
    
    @if(!empty($badge) || $badge === 0)
        <span x-show="!$store.sidebar?.isCollapsed || $store.sidebar?.isHovering"
              x-cloak
              class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white rounded-full {{ $badgeClass }}">
            {{ $badge }}
        </span>
    @endif
    
    @if($isActive)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-indigo-400 rounded-r-full"></span>
    @endif
</a>