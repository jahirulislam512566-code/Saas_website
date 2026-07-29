@extends('layouts.website')

@section('title', 'Creative Agency - ' . config('app.name'))

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #4a7fb5 100%);
    }
    .service-card:hover {
        transform: translateY(-10px);
    }
    .client-logo {
        filter: grayscale(1);
        transition: all 0.3s;
    }
    .client-logo:hover {
        filter: grayscale(0);
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-gradient min-h-screen flex items-center text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-block bg-white/10 backdrop-blur-sm text-white px-4 py-1 rounded-full text-sm font-semibold mb-6">
                🌟 Award-Winning Agency
            </div>
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                We Build Brands <span class="text-primary-300">That Matter</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-primary-100 font-light">Full-service creative agency dedicated to helping you stand out in a crowded market</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <x-button variant="secondary" size="lg" href="{{ route('themes.agency.services') }}" icon="briefcase">Our Services</x-button>
                <x-button variant="outline" size="lg" href="{{ route('themes.agency.contact') }}" icon="mail">Let's Talk</x-button>
            </div>
        </div>
    </div>
</section>

<!-- Client Logos -->
<section class="py-16 bg-gray-50 border-y border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-gray-500 text-sm uppercase tracking-wider mb-8">Trusted by leading brands worldwide</p>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center">
            @foreach($clients ?? $defaultClients as $client)
                <img src="{{ $client['logo'] ?? '/assets/images/placeholder-logo.png' }}" alt="{{ $client['name'] ?? 'Client' }}" class="client-logo w-full h-auto max-h-12 object-contain">
            @endforeach
        </div>
    </div>
</section>

<!-- Services -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">What We Do</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Core Services</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Comprehensive solutions to build, grow, and scale your brand</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($services ?? $defaultServices as $service)
                <div class="service-card bg-white p-8 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-center group">
                    <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition">
                        <x-icon name="{{ $service['icon'] ?? 'star' }}" class="w-10 h-10 text-primary-600 group-hover:text-white transition" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service['title'] ?? 'Service' }}</h3>
                    <p class="text-gray-600 mb-4">{{ $service['description'] ?? 'Service description' }}</p>
                    <x-button variant="outline" size="sm" href="{{ $service['link'] ?? '#' }}" icon="arrow-right">Learn More</x-button>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">What Our Clients Say</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials ?? $defaultTestimonials as $testimonial)
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition">
                    <div class="flex items-center mb-4">
                        <x-avatar name="{{ $testimonial['name'] ?? 'Client' }}" size="md" />
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">{{ $testimonial['name'] ?? 'Client Name' }}</p>
                            <p class="text-sm text-gray-500">{{ $testimonial['position'] ?? 'Position' }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"{{ $testimonial['text'] ?? 'Testimonial text' }}"</p>
                    <div class="mt-4 text-yellow-400">★★★★★</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-primary-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Grow Your Brand?</h2>
        <p class="text-xl text-primary-100 mb-8">Let's create something amazing together. We're here to help you succeed.</p>
        <x-button variant="secondary" size="lg" href="{{ route('themes.agency.contact') }}" icon="arrow-right">Start Your Project</x-button>
    </div>
</section>
@endsection

@php
$defaultClients = [
    ['name' => 'Google', 'logo' => '/assets/images/google-logo.png'],
    ['name' => 'Microsoft', 'logo' => '/assets/images/microsoft-logo.png'],
    ['name' => 'Amazon', 'logo' => '/assets/images/amazon-logo.png'],
    ['name' => 'Apple', 'logo' => '/assets/images/apple-logo.png'],
    ['name' => 'Facebook', 'logo' => '/assets/images/facebook-logo.png'],
    ['name' => 'Twitter', 'logo' => '/assets/images/twitter-logo.png']
];

$defaultServices = [
    ['title' => 'Branding', 'description' => 'Create a strong, memorable brand identity', 'icon' => 'user', 'link' => '#'],
    ['title' => 'Marketing', 'description' => 'Strategic marketing campaigns that deliver results', 'icon' => 'cog', 'link' => '#'],
    ['title' => 'Development', 'description' => 'Custom web and mobile development', 'icon' => 'star', 'link' => '#']
];

$defaultTestimonials = [
    ['name' => 'Sarah Johnson', 'text' => 'They transformed our brand completely. Highly recommended!', 'position' => 'CEO, TechStart'],
    ['name' => 'David Chen', 'text' => 'The team delivered beyond our expectations. Amazing work!', 'position' => 'Marketing Director'],
    ['name' => 'Emily Rodriguez', 'text' => 'Professional, creative, and results-driven. A pleasure to work with.', 'position' => 'Founder, CreativeLab']
];
@endphp