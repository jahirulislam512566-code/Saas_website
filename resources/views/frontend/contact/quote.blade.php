{{-- resources/views/frontend/contact/quote.blade.php --}}
@php
    $pageTitle = 'Request a Quote - AgencyPro';
    $pageDescription = 'Get a customized quote for your project. Tell us about your needs and we\'ll provide a tailored solution.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Request a Quote"
        subtitle="Get a Customized Solution"
        :breadcrumb="[
            ['label' => 'Contact', 'url' => route('contact.index')],
            ['label' => 'Quote']
        ]"
    />

    {{-- Quote Form --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-12">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Custom Quote</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Tell Us About Your Project</h2>
                    <p class="mt-4 text-lg text-[#4a4a4a]">Fill out the form below and we'll provide a tailored quote within 24 hours.</p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-forms.input name="first_name" label="First Name" placeholder="John" required />
                        <x-forms.input name="last_name" label="Last Name" placeholder="Doe" required />
                    </div>

                    <x-forms.input name="email" type="email" label="Email Address" placeholder="john@example.com" required />
                    <x-forms.input name="phone" type="tel" label="Phone Number" placeholder="+1 (555) 123-4567" />

                    <x-forms.select name="project_type" label="Project Type" required
                        :options="[
                            '' => 'Select project type...',
                            'web-development' => 'Web Development',
                            'web-design' => 'Web Design',
                            'ecommerce' => 'E-Commerce',
                            'mobile-app' => 'Mobile App Development',
                            'seo' => 'SEO & Marketing',
                            'maintenance' => 'Maintenance & Support',
                            'consulting' => 'Consulting',
                            'other' => 'Other',
                        ]"
                    />

                    <x-forms.select name="budget" label="Budget Range"
                        :options="[
                            '' => 'Select budget range...',
                            'under-5k' => 'Under $5,000',
                            '5k-10k' => '$5,000 - $10,000',
                            '10k-25k' => '$10,000 - $25,000',
                            '25k-50k' => '$25,000 - $50,000',
                            '50k-100k' => '$50,000 - $100,000',
                            '100k+' => '$100,000+',
                        ]"
                    />

                    <x-forms.textarea name="description" label="Project Description" placeholder="Please describe your project in detail..." rows="6" required />
                    
                    <x-forms.input name="timeline" label="Project Timeline" placeholder="e.g., 2-3 months, ASAP, etc." />

                    <x-forms.upload name="attachments" label="Attachments (Optional)" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" multiple preview />

                    <x-forms.checkbox name="terms" label="I agree to the terms and conditions" required />

                    <x-ui.button variant="primary" size="lg" full-width type="submit">
                        Submit Quote Request
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </x-ui.button>
                </form>
            </div>
        </div>
    </section>
@endsection