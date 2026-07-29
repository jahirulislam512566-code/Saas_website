{{-- resources/views/frontend/pages/privacy.blade.php --}}
@php
    $pageTitle = 'Privacy Policy - AgencyPro';
    $pageDescription = 'Read our privacy policy to understand how we collect, use, and protect your personal information.';
    $lastUpdated = 'December 20, 2024';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Privacy Policy"
        subtitle="Your Privacy Matters"
        :breadcrumb="[['label' => 'Privacy Policy']]"
    >
        <x-slot:actions>
            <span class="text-sm text-[#4a4a4a]">Last Updated: {{ $lastUpdated }}</span>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Policy Content --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto prose prose-lg">
                <div class="bg-gradient-to-br from-[#f8f7ff] to-white rounded-2xl p-8 mb-8 border border-[#6c5ce7]/10">
                    <p class="text-[#0a0a0a] font-medium">At AgencyPro, we take your privacy seriously. This policy describes how we collect, use, and protect your personal information.</p>
                </div>

                <h2>Information We Collect</h2>
                <p>We collect information you provide directly to us, such as when you:</p>
                <ul>
                    <li>Contact us through our website</li>
                    <li>Request a quote or consultation</li>
                    <li>Subscribe to our newsletter</li>
                    <li>Apply for a position</li>
                    <li>Use our services</li>
                </ul>

                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and improve our services</li>
                    <li>Communicate with you about your inquiries</li>
                    <li>Send you relevant updates and marketing materials</li>
                    <li>Process your applications and requests</li>
                    <li>Ensure security and prevent fraud</li>
                </ul>

                <h2>Information Sharing</h2>
                <p>We do not sell, trade, or rent your personal information to third parties. We may share your information with:</p>
                <ul>
                    <li>Trusted service providers who assist us in operating our website and services</li>
                    <li>Legal authorities when required by law</li>
                    <li>With your explicit consent</li>
                </ul>

                <h2>Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                <h2>Your Rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access your personal information</li>
                    <li>Correct inaccurate information</li>
                    <li>Request deletion of your information</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Withdraw consent at any time</li>
                </ul>

                <h2>Cookies</h2>
                <p>We use cookies to enhance your experience on our website. You can control cookie preferences through your browser settings.</p>

                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                <ul>
                    <li>Email: privacy@agencypro.com</li>
                    <li>Phone: +1 (555) 123-4567</li>
                    <li>Address: 123 Innovation Drive, San Francisco, CA 94105</li>
                </ul>
            </div>
        </div>
    </section>
@endsection