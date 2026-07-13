{{-- resources/views/components/website/process-step.blade.php --}}
@props([
    'number' => '01',
    'title' => 'Step Title',
    'description' => 'Step description goes here.'
])

<div class="flex gap-6 items-start">
    <div class="flex-shrink-0">
        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
            {{ $number }}
        </div>
    </div>
    <div>
        <h3 class="text-xl font-semibold text-gray-900">{{ $title }}</h3>
        <p class="mt-2 text-gray-600 leading-relaxed">{{ $description }}</p>
    </div>
</div>