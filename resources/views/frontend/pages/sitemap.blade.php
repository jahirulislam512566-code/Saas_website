{{-- resources/views/frontend/pages/sitemap.blade.php --}}
@php
    $pageTitle = 'Sitemap - AgencyPro';
    $pageDescription = 'Find your way around our website with this comprehensive sitemap.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Sitemap"
        subtitle="Site Navigation"
        :breadcrumb="[['label' => 'Sitemap']]"
    />

    {{-- Sitemap Content --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Site Map</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Navigate Our Website</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Main Pages --}}
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-[#0a0a0a] mb-4">Main Pages</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Home</a></li>
                            <li><a href="{{ route('pages.about') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">About Us</a></li>
                            <li><a href="{{ route('services.index') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Services</a></li>
                            <li><a href="{{ route('portfolio.index') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Portfolio</a></li>
                            <li><a href="{{ route('blog.index') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Blog</a></li>
                            <li><a href="{{ route('contact.index') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Contact</a></li>
                            <li><a href="{{ route('pricing.index') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Pricing</a></li>
                        </ul>
                    </div>

                    {{-- Services --}}
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-[#0a0a0a] mb-4">Services</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('services.web-development') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Web Development</a></li>
                            <li><a href="{{ route('services.web-design') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Web Design</a></li>
                            <li><a href="{{ route('services.ecommerce') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">E-Commerce</a></li>
                            <li><a href="{{ route('services.seo') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">SEO & Marketing</a></li>
                            <li><a href="{{ route('services.maintenance') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Maintenance & Support</a></li>
                            <li><a href="{{ route('services.hosting') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Hosting Solutions</a></li>
                        </ul>
                    </div>

                    {{-- Legal Pages --}}
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-[#0a0a0a] mb-4">Legal & Information</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('pages.faq') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">FAQ</a></li>
                            <li><a href="{{ route('pages.careers') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Careers</a></li>
                            <li><a href="{{ route('pages.privacy') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Privacy Policy</a></li>
                            <li><a href="{{ route('pages.terms') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Terms of Service</a></li>
                            <li><a href="{{ route('pages.refund') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Refund Policy</a></li>
                            <li><a href="{{ route('pages.cookie') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Cookie Policy</a></li>
                            <li><a href="{{ route('pages.sitemap') }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">Sitemap</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="mt-12 text-center">
                    <p class="text-[#4a4a4a]">Can't find what you're looking for?</p>
                    <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 mt-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                        Contact Us
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection