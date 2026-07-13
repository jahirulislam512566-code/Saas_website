{{-- resources/views/components/website/value-card.blade.php --}}
@props([
    'icon' => 'fa-star',
    'title' => 'Value Title',
    'description' => 'Value description goes here.'
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm hover:shadow-xl transition border border-gray-100 p-6 text-center group']) }}>
    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-600 transition-colors duration-300">
        <i class="fas {{ $icon }} text-2xl text-indigo-600 group-hover:text-white transition-colors duration-300"></i>
    </div>
    <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
    <p class="mt-2 text-gray-600 text-sm">{{ $description }}</p>
</div>