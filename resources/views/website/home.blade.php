@extends('layouts.website')

@section('title', 'Build Beautiful Websites Without Coding - Buildora')
@section('description', 'Create professional business websites in minutes. No coding required. Hosting included. SEO ready. Fast. Secure.')

@section('content')
    {{-- Hero Section --}}
    @include('website.partials.hero2')
    
    {{-- <x-hero/> --}}
    {{-- Trusted Companies --}}
    @include('website.partials.trusted-by')
     
    {{-- Statistics --}}
    {{-- <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 15000, 200)">
                    <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                        <span x-text="count.toLocaleString()">0</span>+
                    </div>
                    <p class="text-gray-600 font-medium">Websites Created</p>
                </div>
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 120, 400)">
                    <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                        <span x-text="count">0</span>+
                    </div>
                    <p class="text-gray-600 font-medium">Templates</p>
                </div>
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 99.99, 600)">
                    <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                        <span x-text="count">0</span>%
                    </div>
                    <p class="text-gray-600 font-medium">Uptime</p>
                </div>
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 35, 800)">
                    <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                        <span x-text="count">0</span>
                    </div>
                    <p class="text-gray-600 font-medium">Countries</p>
                </div>
            </div>
        </div>
    </section> --}}

    @include('website.partials.statistics')
    {{-- Features Preview --}}
    @include('website.partials.features-preview')

    {{-- Template Showcase --}}
    @include('website.partials.template-showcase')

    {{-- How It Works --}}
    @include('website.partials.how-it-works')

    {{-- Builder Demo --}}
    @include('website.partials.builder-demo')

    {{-- Integrations --}}
    @include('website.partials.integrations')

    {{-- Testimonials --}}
    @include('website.partials.testimonials')

    {{-- Pricing Preview --}}
    @include('website.partials.pricing-preview')

    {{-- FAQ --}}
    @include('website.partials.faq')

    {{-- Call To Action --}}
    @include('website.partials.cta')

    {{-- Newsletter --}}
    @include('website.partials.newsletter')
@endsection

@push('styles')
<style>
    @keyframes scroll {
        0%, 100% { transform: translateY(0); opacity: 1; }
        50% { transform: translateY(8px); opacity: 0.5; }
    }
    .animate-scroll {
        animation: scroll 2s ease-in-out infinite;
    }
    .animation-delay-1000 { animation-delay: 1s; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.1); opacity: 0.2; }
    }
    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }
</style>
@endpush