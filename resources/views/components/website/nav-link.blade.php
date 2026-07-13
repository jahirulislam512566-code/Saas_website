{{-- resources/views/components/website/nav-link.blade.php --}}
@props(['href', 'active' => false])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'px-4 py-2 text-sm font-medium rounded-lg transition-colors ' . ($active ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50')]) }}>
    {{ $slot }}
</a>