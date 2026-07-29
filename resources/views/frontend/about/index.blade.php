{{-- resources/views/frontend/about/index.blade.php --}}
@php
    $pageTitle = 'About Us - AgencyPro';
    $pageDescription = 'Learn about AgencyPro, our mission, vision, history, team, and values. We\'re a digital agency dedicated to creating exceptional experiences.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="Organization" :data="[
        'name' => 'AgencyPro',
        'url' => url('/'),
        'description' => $pageDescription,
        'logo' => asset('images/logo.png'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '123 Innovation Drive',
            'addressLocality' => 'San Francisco',
            'addressRegion' => 'CA',
            'postalCode' => '94105',
            'addressCountry' => 'US',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+1-555-123-4567',
            'contactType' => 'Sales',
            'availableLanguage' => ['English'],
        ],
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
            'https://dribbble.com/agencypro',
            'https://github.com/agencypro',
        ],
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="About AgencyPro"
        subtitle="Our Story"
        :breadcrumb="[['label' => 'About']]"
    >
        <x-slot:actions>
            <a href="#team" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                Meet Our Team
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Mission & Vision --}}
    @include('frontend.about.mission')
    
    {{-- Values --}}
    @include('frontend.about.values')
    
    {{-- History --}}
    @include('frontend.about.history')
    
    {{-- Stats --}}
    <x-sections.stats 
        title="Our Journey in Numbers"
        subtitle="Impact"
        description="Over the years, we've grown and evolved, but our commitment to excellence has never wavered."
        :stats="[
            ['value' => '8+', 'label' => 'Years in Business'],
            ['value' => '500+', 'label' => 'Projects Delivered'],
            ['value' => '98%', 'label' => 'Client Satisfaction'],
            ['value' => '50+', 'label' => 'Team Members'],
        ]"
    />
    
    {{-- Team --}}
    @include('frontend.about.team')
    
    {{-- CTA --}}
    <x-sections.cta 
        title="Want to Join Our Team?"
        subtitle="Careers"
        description="We're always looking for talented individuals who share our passion for innovation and excellence."
        primary-text="View Open Positions"
        primary-link="#careers"
        secondary-text="Contact Us"
        secondary-link="#contact"
        variant="default"
    />
@endsection