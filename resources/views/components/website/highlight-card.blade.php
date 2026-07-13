{{-- resources/views/components/website/highlight-card.blade.php --}}
@props([
    'icon' => 'fa-check-circle',
    'title' => 'Feature Title',
    'description' => 'Feature description goes here.'
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition border border-gray-100 group']) }}>
    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-600 transition-colors duration-300">
        <i class="fas {{ $icon }} text-2xl text-indigo-600 group-hover:text-white transition-colors duration-300"></i>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
    <p class="mt-2 text-sm text-gray-600">{{ $description }}</p>
</div>