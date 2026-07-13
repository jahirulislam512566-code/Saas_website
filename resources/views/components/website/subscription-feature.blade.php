{{-- resources/views/components/website/subscription-feature.blade.php --}}
@props([
    'icon' => 'fa-check-circle',
    'title' => 'Feature Title',
    'features' => []
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-100 p-6']) }}>
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="fas {{ $icon }}"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
    </div>
    <ul class="space-y-2">
        @foreach($features as $feature)
            <li class="flex items-start gap-2 text-sm text-gray-600">
                <i class="fas fa-check-circle text-indigo-500 mt-0.5"></i>
                <span>{{ $feature }}</span>
            </li>
        @endforeach
    </ul>
</div>