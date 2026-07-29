{{-- resources/views/components/website/filter-tag.blade.php --}}
@props(['active' => false])

<button 
    {{ $attributes->merge(['class' => 'px-4 py-2 text-sm font-medium rounded-full transition-colors duration-200 ' . ($active ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')]) }}
>
    {{ $slot }}
</button>