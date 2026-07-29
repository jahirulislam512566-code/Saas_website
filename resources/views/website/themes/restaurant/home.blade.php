@extends('layouts.website')

@section('title', 'Welcome to Our Restaurant - ' . config('app.name'))

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/assets/images/restaurant-hero.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .dish-card:hover {
        transform: translateY(-8px);
    }
    .floating-element {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section min-h-screen flex items-center relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white relative z-10">
        <div class="inline-block bg-primary-600/90 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold mb-6 tracking-wider uppercase floating-element">
            Since 2010
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
            Dine in <span class="text-primary-400">Elegance</span>
        </h1>
        <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto text-gray-300 font-light">
            Experience culinary excellence in a warm, inviting atmosphere
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-button variant="primary" size="lg" icon="calendar" href="{{ route('themes.restaurant.contact') }}" class="shadow-lg shadow-primary-600/30">
                Book a Table
            </x-button>
            <x-button variant="outline" size="lg" icon="menu" href="{{ route('themes.restaurant.menu') }}" class="border-white/30 hover:bg-white/10">
                View Menu
            </x-button>
        </div>
        
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                <div class="text-3xl font-bold text-primary-400">50+</div>
                <div class="text-sm text-gray-300 mt-1">Premium Dishes</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                <div class="text-3xl font-bold text-primary-400">15+</div>
                <div class="text-sm text-gray-300 mt-1">Years Experience</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                <div class="text-3xl font-bold text-primary-400">4.9★</div>
                <div class="text-sm text-gray-300 mt-1">Guest Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Highlights -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Our Specialties</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Signature Dishes</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Each dish is crafted with passion and the finest ingredients</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredDishes ?? $defaultFeaturedDishes as $dish)
                <div class="dish-card bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="h-56 bg-gray-200 relative overflow-hidden">
                        <img src="{{ $dish['image'] ?? '/assets/images/placeholder.jpg' }}" alt="{{ $dish['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 right-4 bg-primary-600 text-white px-4 py-1 rounded-full text-sm font-bold shadow-lg">
                            {{ $dish['price'] ?? '$0' }}
                        </span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">{{ $dish['name'] ?? 'Dish Name' }}</h3>
                        <p class="text-gray-600 mt-2 text-sm">{{ $dish['description'] ?? 'Delicious dish description' }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-1">
                                <span class="text-yellow-400 text-sm">★★★★★</span>
                                <span class="text-xs text-gray-500">(24 reviews)</span>
                            </div>
                            <x-button variant="outline" size="sm" icon="shopping-cart">Order Now</x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-10">
            <x-button variant="primary" href="{{ route('themes.restaurant.menu') }}" icon="arrow-right">View Full Menu</x-button>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-primary-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-primary-300 font-semibold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2">What Our Guests Say</h2>
            <div class="w-24 h-1 bg-primary-400 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials ?? $defaultTestimonials as $testimonial)
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                    <div class="flex items-center mb-4">
                        <x-avatar name="{{ $testimonial['name'] ?? 'Guest' }}" size="md" />
                        <div class="ml-3">
                            <p class="font-semibold">{{ $testimonial['name'] ?? 'Guest' }}</p>
                            <p class="text-sm text-primary-300">{{ $testimonial['position'] ?? 'Guest' }}</p>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"{{ $testimonial['text'] ?? 'Amazing experience!' }}"</p>
                    <div class="mt-4 text-yellow-400">★★★★★</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Reservation CTA -->
<section class="py-20 bg-primary-600 text-white relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready for an Unforgettable Experience?</h2>
        <p class="text-xl text-primary-100 mb-8">Book your table now and let us take you on a culinary journey</p>
        <x-button variant="secondary" size="lg" icon="calendar" href="{{ route('themes.restaurant.contact') }}" class="shadow-2xl shadow-primary-700/50">
            Make a Reservation
        </x-button>
        <p class="mt-4 text-sm text-primary-200">📞 Call us: +1 (555) 123-4567</p>
    </div>
</section>
@endsection

@php
// Default data for the theme
$defaultFeaturedDishes = [
    ['name' => 'Grilled Salmon', 'price' => '$28', 'description' => 'Fresh Atlantic salmon with seasonal vegetables', 'image' => '/assets/images/salmon.jpg'],
    ['name' => 'Beef Tenderloin', 'price' => '$34', 'description' => 'Premium beef tenderloin with truffle mash', 'image' => '/assets/images/steak.jpg'],
    ['name' => 'Lobster Bisque', 'price' => '$18', 'description' => 'Rich and creamy lobster bisque', 'image' => '/assets/images/bisque.jpg']
];

$defaultTestimonials = [
    ['name' => 'John Smith', 'text' => 'Best dining experience I\'ve had in years. The attention to detail is remarkable.', 'position' => 'Food Critic'],
    ['name' => 'Emily Davis', 'text' => 'The ambiance and service were exceptional. Will definitely come back.', 'position' => 'Regular Customer'],
    ['name' => 'Michael Brown', 'text' => 'Incredible food and wonderful staff. A true gem in the city.', 'position' => 'VIP Guest']
];
@endphp