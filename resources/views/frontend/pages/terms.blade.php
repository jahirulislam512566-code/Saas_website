{{-- resources/views/frontend/pages/terms.blade.php --}}
@php
    $pageTitle = 'Terms of Service - AgencyPro';
    $pageDescription = 'Read our terms of service to understand the terms and conditions governing the use of our services.';
    $lastUpdated = 'December 20, 2024';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Terms of Service"
        subtitle="Terms & Conditions"
        :breadcrumb="[['label' => 'Terms of Service']]"
    >
        <x-slot:actions>
            <span class="text-sm text-[#4a4a4a]">Last Updated: {{ $lastUpdated }}</span>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Terms Content --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto prose prose-lg">
                <div class="bg-gradient-to-br from-[#f8f7ff] to-white rounded-2xl p-8 mb-8 border border-[#6c5ce7]/10">
                    <p class="text-[#0a0a0a] font-medium">These Terms of Service govern your use of our services. By using our services, you agree to these terms.</p>
                </div>

                <h2>Acceptance of Terms</h2>
                <p>By accessing or using our services, you agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree to these terms, please do not use our services.</p>

                <h2>Our Services</h2>
                <p>AgencyPro provides digital agency services including web development, design, mobile development, digital strategy, and marketing services. Our services are provided on an as-is basis.</p>

                <h2>Client Responsibilities</h2>
                <p>You are responsible for:</p>
                <ul>
                    <li>Providing accurate information and materials</li>
                    <li>Cooperating with our team during projects</li>
                    <li>Reviewing and approving deliverables</li>
                    <li>Maintaining the confidentiality of your account information</li>
                </ul>

                <h2>Payments and Fees</h2>
                <p>All fees are as stated in your agreement with us. Payment terms are specified in your contract. Late payments may result in service suspension.</p>

                <h2>Intellectual Property</h2>
                <p>All intellectual property created during our engagement belongs to the client upon full payment. However, we retain the right to showcase the work in our portfolio.</p>

                <h2>Confidentiality</h2>
                <p>Both parties agree to maintain the confidentiality of proprietary information shared during the engagement.</p>

                <h2>Warranties and Disclaimers</h2>
                <p>Our services are provided on an "as is" basis. While we strive for excellence, we do not guarantee error-free or uninterrupted service.</p>

                <h2>Limitation of Liability</h2>
                <p>To the maximum extent permitted by law, AgencyPro shall not be liable for any indirect, incidental, or consequential damages.</p>

                <h2>Termination</h2>
                <p>Either party may terminate the engagement with written notice. Termination does not affect obligations to pay for services already rendered.</p>

                <h2>Governing Law</h2>
                <p>These terms are governed by the laws of the State of California, without regard to its conflict of laws principles.</p>

                <h2>Changes to Terms</h2>
                <p>We reserve the right to update these terms at any time. Changes will be effective upon posting to this page.</p>

                <h2>Contact</h2>
                <p>If you have questions about these terms, please contact us at:</p>
                <ul>
                    <li>Email: legal@agencypro.com</li>
                    <li>Phone: +1 (555) 123-4567</li>
                </ul>
            </div>
        </div>
    </section>
@endsection