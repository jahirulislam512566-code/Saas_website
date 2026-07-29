{{-- resources/views/frontend/contact/index.blade.php --}}
@php
    $pageTitle = 'Contact Us - AgencyPro';
    $pageDescription = 'Get in touch with AgencyPro. We\'d love to hear about your project and discuss how we can help.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="Organization" :data="[
        'name' => 'AgencyPro',
        'url' => url('/contact'),
        'description' => $pageDescription,
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
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Get In Touch"
        subtitle="Contact Us"
        :breadcrumb="[['label' => 'Contact']]"
    >
        <x-slot:actions>
            <a href="{{ route('contact.quote') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                Request a Quote
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </a>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Contact Form & Info --}}
    <x-sections.contact 
        title="Let's Talk About Your Project"
        subtitle="Get In Touch"
        description="Have a project in mind? We'd love to hear from you. Fill out the form below and we'll get back to you within 24 hours."
        form-action="{{ route('contact.submit') }}"
        form-method="POST"
        button-text="Send Message →"
        :info="[
            [
                'icon' => '📧',
                'title' => 'Email Us',
                'value' => 'hello@agencypro.com',
            ],
            [
                'icon' => '📍',
                'title' => 'Visit Us',
                'value' => '123 Innovation Drive, San Francisco, CA 94105',
            ],
            [
                'icon' => '📞',
                'title' => 'Call Us',
                'value' => '+1 (555) 123-4567',
            ],
            [
                'icon' => '🕐',
                'title' => 'Working Hours',
                'value' => 'Mon - Fri: 9:00 AM - 6:00 PM PST',
            ],
        ]"
    />

    {{-- Map --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.091647213853!2d-122.3999846846816!3d37.78774877975954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085808e2b9a4b5b%3A0x8b2b5b5b5b5b5b5b!2sSan%20Francisco%2C%20CA!5e0!3m2!1sen!2sus!4v1700000000000"
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
@endsection