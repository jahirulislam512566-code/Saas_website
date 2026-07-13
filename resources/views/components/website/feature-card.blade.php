{{-- resources/views/components/website/feature-card.blade.php --}}
@props([
    'icon' => 'fa-rocket',
    'title' => 'Feature Title',
    'description' => 'Feature description goes here.',
])

<div {{ $attributes->merge(['class' => 'group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1']) }}>
    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
        <i class="fas {{ $icon }} text-xl"></i>
    </div>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $title }}</h3>
    <p class="mt-2 text-gray-600 text-sm leading-relaxed">{{ $description }}</p>
</div>