{{-- resources/views/components/website/contact-info.blade.php --}}
@props([
    'icon' => 'fa-envelope',
    'title' => 'Contact Title',
    'detail' => 'Contact detail goes here.'
])

<div {{ $attributes->merge(['class' => 'flex items-start gap-4']) }}>
    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
        <i class="fas {{ $icon }} text-xl"></i>
    </div>
    <div>
        <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
        <p class="text-sm text-gray-600">{{ $detail }}</p>
    </div>
</div>