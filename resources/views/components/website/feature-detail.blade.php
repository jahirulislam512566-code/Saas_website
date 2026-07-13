{{-- resources/views/components/website/feature-detail.blade.php --}}
@props([
    'icon' => 'fa-rocket',
    'title' => 'Feature Title',
    'description' => 'Feature description goes here.',
    'image' => null,
    'reverse' => false
])

<div class="flex flex-col {{ $reverse ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center gap-8 bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition">
    <div class="w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fas {{ $icon }} text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $title }}</h3>
        </div>
        <p class="text-gray-600 leading-relaxed">{{ $description }}</p>
    </div>
    <div class="w-full md:w-1/2">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-64 object-cover rounded-xl shadow-md">
        @else
            <div class="w-full h-64 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-image text-4xl text-indigo-300"></i>
            </div>
        @endif
    </div>
</div>