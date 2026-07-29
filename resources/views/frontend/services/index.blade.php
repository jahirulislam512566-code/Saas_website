{{-- resources/views/frontend/services/index.blade.php --}}
@php
    $pageTitle = 'Our Services - AgencyPro';
    $pageDescription = 'Explore our comprehensive digital services including web development, design, e-commerce, SEO, maintenance, and hosting solutions.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="WebSite" :data="[
        'name' => 'AgencyPro Services',
        'url' => url('/services'),
        'description' => $pageDescription,
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Our Services"
        subtitle="What We Do"
        :breadcrumb="[['label' => 'Services']]"
    >
        <x-slot:actions>
            <a href="#contact" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                Need a Custom Solution?
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Services Overview --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Comprehensive Solutions</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Everything You Need to Succeed Online</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">From strategy to execution, we provide end-to-end digital services designed to help your business thrive in the digital landscape.</p>
            </div>

            {{-- Services Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Web Development --}}
                <a href="{{ route('services.web-development') }}" class="group bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-[#6c5ce7]/20 no-underline">
                    <div class="w-16 h-16 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                        💻
                    </div>
                    <h3 class="text-xl font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Web Development</h3>
                    <p class="text-[#4a4a4a] mt-2">Custom web applications built with modern technologies for optimal performance and scalability.</p>
                    <span class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium group-hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>

                {{-- Web Design --}}
                <a href="{{ route('services.web-design') }}" class="group bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-[#6c5ce7]/20 no-underline">
                    <div class="w-16 h-16 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                        🎨
                    </div>
                    <h3 class="text-xl font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Web Design</h3>
                    <p class="text-[#4a4a4a] mt-2">Beautiful, user-centered designs that create memorable experiences and drive engagement.</p>
                    <span class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium group-hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>

                {{-- E-Commerce --}}
                <a href="{{ route('services.ecommerce') }}" class="group bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-[#6c5ce7]/20 no-underline">
                    <div class="w-16 h-16 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                        🛒
                    </div>
                    <h3 class="text-xl font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">E-Commerce Solutions</h3>
                    <p class="text-[#4a4a4a] mt-2">Powerful online stores with seamless payment processing and exceptional user experiences.</p>
                    <span class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium group-hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>

                {{-- SEO --}}
                <a href="{{ route('services.seo') }}" class="group bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-[#6c5ce7]/20 no-underline">
                    <div class="w-16 h-16 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                        🔍
                    </div>
                    <h3 class="text-xl font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">SEO & Marketing</h3>
                    <p class="text-[#4a4a4a] mt-2">Data-driven strategies to increase visibility, attract more customers, and grow your business.</p>
                    <span class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium group-hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>

                {{-- Maintenance --}}
                <a href="{{ route('services.maintenance') }}" class="group bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-[#6c5ce7]/20 no-underline">
                    <div class="w-16 h-16 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                        🔧
                    </div>
                    <h3 class="text-xl font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Maintenance & Support</h3>
                    <p class="text-[#4a4a4a] mt-2">Reliable maintenance and support services to keep your digital assets running smoothly.</p>
                    <span class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium group-hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>

                {{-- Hosting --}}
                <a href="{{ route('services.hosting') }}" class="group bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-[#6c5ce7]/20 no-underline">
                    <div class="w-16 h-16 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                        ☁️
                    </div>
                    <h3 class="text-xl font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Hosting Solutions</h3>
                    <p class="text-[#4a4a4a] mt-2">Fast, secure, and reliable hosting solutions optimized for performance and uptime.</p>
                    <span class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium group-hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Why Choose Us</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">What Sets Us Apart</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl mb-3">⭐</div>
                    <h4 class="font-bold text-[#0a0a0a]">Expert Team</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">15+ years of combined experience</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">🚀</div>
                    <h4 class="font-bold text-[#0a0a0a]">Fast Delivery</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Agile methodology, quick turnaround</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">🔒</div>
                    <h4 class="font-bold text-[#0a0a0a]">Secure & Reliable</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Enterprise-grade security standards</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">💬</div>
                    <h4 class="font-bold text-[#0a0a0a]">24/7 Support</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Always here when you need us</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-sections.cta 
        title="Ready to Get Started?"
        subtitle="Let's Build Something Amazing"
        description="Contact us today for a free consultation and let's discuss how we can help your business grow."
        primary-text="Start Your Project"
        primary-link="#contact"
        secondary-text="View Our Portfolio"
        secondary-link="{{ route('portfolio.index') }}"
        variant="default"
    />
@endsection