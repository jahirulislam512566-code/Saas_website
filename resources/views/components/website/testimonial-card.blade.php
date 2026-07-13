{{-- resources/views/components/website/testimonial-card.blade.php --}}
@props([
    'name' => 'John Doe',
    'role' => 'CEO, Company',
    'avatar' => 'JD',
    'quote' => 'This is an amazing testimonial from our satisfied customer.',
    'large' => false,
    'rating' => 5
])

@php
    $classes = $large 
        ? 'bg-white rounded-2xl shadow-sm p-8 border border-gray-100' 
        : 'bg-white rounded-2xl shadow-sm p-6 border border-gray-100';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <!-- Rating Stars -->
    @if($rating > 0)
        <div class="flex gap-1 mb-4 text-yellow-400">
            @for($i = 0; $i < $rating; $i++)
                <i class="fas fa-star"></i>
            @endfor
            @for($i = $rating; $i < 5; $i++)
                <i class="far fa-star"></i>
            @endfor
        </div>
    @endif
    
    <!-- Quote -->
    <p class="text-gray-600 leading-relaxed italic">"{{ $quote }}"</p>
    
    <!-- Author -->
    <div class="mt-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold text-sm">
            {{ $avatar }}
        </div>
        <div>
            <p class="font-semibold text-gray-900">{{ $name }}</p>
            <p class="text-sm text-gray-500">{{ $role }}</p>
        </div>
    </div>
</div>