{{-- resources/views/components/website/mobile-nav-link.blade.php --}}
@props(['href', 'active' => false])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'block px-4 py-2 text-base font-medium rounded-lg transition-colors ' . ($active ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50')]) }}>
    {{ $slot }}
</a>