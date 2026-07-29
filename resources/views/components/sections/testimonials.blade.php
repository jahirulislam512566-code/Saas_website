{{-- resources/views/components/sections/testimonials.blade.php --}}

@props([
    'title' => 'What Our Clients Say',
    'subtitle' => 'Testimonials from our satisfied clients',
    'testimonials' => [],
    'limit' => 6,
    'showAll' => false,
])

<div class="testimonials-section py-5">
    <div class="container">
        {{-- Section Header --}}
        <div class="text-center mb-5">
            <h2 class="section-title">{{ e($title) }}</h2>
            <p class="section-subtitle text-muted">{{ e($subtitle) }}</p>
        </div>

        {{-- Testimonials Grid --}}
        @if($testimonials && count($testimonials) > 0)
            <div class="row g-4">
                @foreach($testimonials as $testimonial)
                    <div class="col-md-4">
                        <x-cards.testimonial-card :testimonial="$testimonial" />
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">No testimonials available.</p>
            </div>
        @endif

        {{-- Show All Button --}}
        @if($showAll)
            <div class="text-center mt-4">
                <a href="{{ route('testimonials.index') }}" class="btn btn-primary">
                    View All Testimonials
                </a>
            </div>
        @endif
    </div>
</div>