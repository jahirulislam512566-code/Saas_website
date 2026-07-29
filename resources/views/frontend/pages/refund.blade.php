{{-- resources/views/frontend/pages/refund.blade.php --}}
@php
    $pageTitle = 'Refund Policy - AgencyPro';
    $pageDescription = 'Read our refund policy to understand our commitment to client satisfaction and our refund procedures.';
    $lastUpdated = 'December 20, 2024';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Refund Policy"
        subtitle="Our Commitment to Satisfaction"
        :breadcrumb="[['label' => 'Refund Policy']]"
    >
        <x-slot:actions>
            <span class="text-sm text-[#4a4a4a]">Last Updated: {{ $lastUpdated }}</span>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Refund Policy Content --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto prose prose-lg">
                <div class="bg-gradient-to-br from-[#f8f7ff] to-white rounded-2xl p-8 mb-8 border border-[#6c5ce7]/10">
                    <p class="text-[#0a0a0a] font-medium">We're committed to your satisfaction. Here's our refund policy and how we handle disputes.</p>
                </div>

                <h2>Our Refund Policy</h2>
                <p>At AgencyPro, we strive to deliver exceptional value and quality in all our services. We understand that sometimes things don't work out as expected.</p>

                <h2>When You Can Request a Refund</h2>
                <ul>
                    <li><strong>Within 14 Days:</strong> For new contracts, you may request a full refund within 14 days of signing if work has not commenced.</li>
                    <li><strong>Satisfaction Guarantee:</strong> If you're not satisfied with our work, we'll work with you to make it right or provide a partial refund.</li>
                    <li><strong>Service Interruption:</strong> If we fail to deliver services as agreed, you may be eligible for a prorated refund.</li>
                </ul>

                <h2>What We Refund</h2>
                <ul>
                    <li>Prepaid services not yet delivered</li>
                    <li>Unused retainer hours</li>
                    <li>Services that don't meet agreed specifications</li>
                </ul>

                <h2>What We Don't Refund</h2>
                <ul>
                    <li>Work that has been completed and approved</li>
                    <li>Third-party costs (software, hosting, etc.)</li>
                    <li>Custom development beyond agreed scope</li>
                    <li>Change requests after project completion</li>
                </ul>

                <h2>Refund Process</h2>
                <ol>
                    <li>Contact us explaining your reasons for requesting a refund</li>
                    <li>Our team will review your request within 3-5 business days</li>
                    <li>If approved, we'll process the refund to your original payment method</li>
                    <li>Refunds are typically processed within 7-10 business days</li>
                </ol>

                <h2>Dispute Resolution</h2>
                <p>If you're unhappy with our services, please contact us directly at support@agencypro.com. We'll work to resolve the issue to your satisfaction.</p>

                <h2>Contact Us</h2>
                <p>For refund requests or questions, please contact:</p>
                <ul>
                    <li>Email: refunds@agencypro.com</li>
                    <li>Phone: +1 (555) 123-4567</li>
                </ul>
            </div>
        </div>
    </section>
@endsection