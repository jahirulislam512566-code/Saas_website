{{-- resources/views/components/theme-nav-link.blade.php --}}
@props(['href', 'active' => false])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'px-4 py-2 text-sm font-medium rounded-full transition-all duration-300 ' . ($active ? 'text-primary-600 bg-primary-50 shadow-sm' : 'text-white hover:text-primary-600 hover:bg-white/10')]) }}>
    {{ $slot }}
</a>