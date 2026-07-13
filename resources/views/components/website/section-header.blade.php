{{-- resources/views/components/website/section-header.blade.php --}}
@props([
    'title' => 'Section Title',
    'subtitle' => 'Subtitle goes here',
    'alignment' => 'center',
    'badge' => null,
])

<div class="{{ $alignment === 'center' ? 'text-center' : 'text-left' }} max-w-3xl {{ $alignment === 'center' ? 'mx-auto' : '' }} mb-12">
    @if($badge)
        <div class="mb-4">
            <span class="px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">
                {{ $badge }}
            </span>
        </div>
    @endif
    
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
        {{ $title }}
    </h2>
    
    <p class="mt-4 text-lg text-gray-600">
        {{ $subtitle }}
    </p>
    
    @isset($action)
        <div class="mt-6">
            {{ $action }}
        </div>
    @endisset
</div>