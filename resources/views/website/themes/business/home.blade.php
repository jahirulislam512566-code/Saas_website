@extends('website.layouts.app')

@section('title', 'Home')
@section('subtitle', 'Business')

@section('content')
    <!-- Hero -->
    @section('hero-title', 'Build Your Business Online')
    @section('hero-subtitle', 'Professional websites for modern businesses.')
    @section('hero-actions')
        <x-website::button href="{{ route('register') }}" variant="primary">Start Free Trial</x-website::button>
        <x-website::button href="#features" variant="secondary">Learn More</x-website::button>
    @endsection
    @include('website.components.hero')

    <!-- Features -->
    @section('features-title', 'Business Tools')
    @section('features-subtitle', 'Everything you need to run your business.')
    @section('features-items')
        <div class="bg-gray-50 p-6 rounded-xl">
            <h3 class="font-semibold">CRM Integration</h3>
            <p class="text-gray-600">Manage leads and customers seamlessly.</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-xl">
            <h3 class="font-semibold">Invoicing</h3>
            <p class="text-gray-600">Create and send invoices in seconds.</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-xl">
            <h3 class="font-semibold">Analytics</h3>
            <p class="text-gray-600">Track your performance in real time.</p>
        </div>
    @endsection
    @include('website.components.features')

    <!-- CTA -->
    @section('cta-title', 'Start Your Business Today')
    @section('cta-subtitle', 'Get a 14‑day free trial, no credit card required.')
    @section('cta-buttons')
        <x-website::button href="{{ route('register') }}" variant="primary">Get Started</x-website::button>
    @endsection
    @include('website.components.cta')
@endsection