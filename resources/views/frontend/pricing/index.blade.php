{{-- resources/views/frontend/pricing/index.blade.php --}}
@php
    $pageTitle = 'Pricing - AgencyPro';
    $pageDescription = 'Choose the perfect pricing plan for your business. Flexible options designed to scale with your needs.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="WebSite" :data="[
        'name' => 'AgencyPro Pricing',
        'url' => url('/pricing'),
        'description' => $pageDescription,
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Simple, Transparent Pricing"
        subtitle="Choose Your Plan"
        :breadcrumb="[['label' => 'Pricing']]"
    >
        <x-slot:actions>
            <a href="{{ route('pricing.compare') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                Compare Plans
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
            </a>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Pricing Plans --}}
    <x-sections.pricing 
        title="Choose Your Plan"
        subtitle="Flexible Pricing"
        description="Select the perfect plan for your business needs. All plans include a 14-day free trial with full access to all features."
        billing="true"
        default-billing="monthly"
        :plans="[
            [
                'name' => 'Starter',
                'price' => '29',
                'currency' => '$',
                'period' => 'month',
                'description' => 'Perfect for small businesses and startups just getting started.',
                'features' => [
                    '1 Project',
                    'Basic Support',
                    '5GB Storage',
                    'Analytics Dashboard',
                    'Email Support',
                    'Monthly Reports',
                    'Basic SEO Tools',
                ],
                'ctaText' => 'Start Free Trial',
                'ctaLink' => '#contact',
                'highlighted' => false,
            ],
            [
                'name' => 'Professional',
                'price' => '99',
                'currency' => '$',
                'period' => 'month',
                'description' => 'Ideal for growing teams and businesses with more complex needs.',
                'features' => [
                    '5 Projects',
                    'Priority Support',
                    '50GB Storage',
                    'Advanced Analytics',
                    'API Access',
                    'Team Collaboration',
                    '24/7 Support',
                    'Custom Integrations',
                    'Advanced SEO Tools',
                ],
                'ctaText' => 'Start Free Trial',
                'ctaLink' => '#contact',
                'highlighted' => true,
                'badge' => 'Most Popular',
            ],
            [
                'name' => 'Enterprise',
                'price' => '299',
                'currency' => '$',
                'period' => 'month',
                'description' => 'For large organizations with complex requirements and dedicated needs.',
                'features' => [
                    'Unlimited Projects',
                    'Dedicated Support',
                    '500GB Storage',
                    'Custom Analytics',
                    'Advanced Security',
                    'Custom Development',
                    'SLA Guarantee',
                    'Training & Onboarding',
                    'White-label Solutions',
                    '24/7 Dedicated Team',
                ],
                'ctaText' => 'Contact Sales',
                'ctaLink' => '#contact',
                'highlighted' => false,
                'badge' => 'Enterprise',
            ],
        ]"
    />

    {{-- Additional Services --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Add-On Services</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Customize Your Package</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">Need something specific? We offer additional services to complement your plan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="text-3xl mb-3">⚡</div>
                    <h4 class="font-bold text-[#0a0a0a]">Performance Optimization</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Speed and performance tuning for your applications.</p>
                    <span class="inline-block mt-3 text-sm font-semibold text-[#6c5ce7]">+$49/mo</span>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="text-3xl mb-3">🛡️</div>
                    <h4 class="font-bold text-[#0a0a0a]">Security Suite</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Advanced security monitoring and vulnerability scanning.</p>
                    <span class="inline-block mt-3 text-sm font-semibold text-[#6c5ce7]">+$99/mo</span>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="text-3xl mb-3">🎨</div>
                    <h4 class="font-bold text-[#0a0a0a]">Design Services</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Ongoing design support and asset creation.</p>
                    <span class="inline-block mt-3 text-sm font-semibold text-[#6c5ce7]">+$79/mo</span>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <x-sections.faq 
        title="Pricing FAQs"
        subtitle="Common Questions"
        description="Find answers to common questions about our pricing and plans."
        columns="2"
        :items="[
            [
                'question' => 'Can I switch plans later?',
                'answer' => 'Yes, you can upgrade or downgrade your plan at any time. Changes take effect at the start of your next billing cycle.'
            ],
            [
                'question' => 'Is there a long-term contract?',
                'answer' => 'No, all our plans are month-to-month with no long-term commitment. You can cancel anytime.'
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major credit cards, PayPal, and bank transfers for annual plans.'
            ],
            [
                'question' => 'Do you offer discounts for non-profits?',
                'answer' => 'Yes, we offer special pricing for non-profit organizations. Contact our sales team for details.'
            ],
        ]"
    />

    {{-- CTA --}}
    <x-sections.cta 
        title="Need a Custom Solution?"
        subtitle="Let's Talk"
        description="Not sure which plan is right for you? Contact us for a personalized recommendation."
        primary-text="Contact Sales"
        primary-link="#contact"
        secondary-text="Use Our Calculator"
        secondary-link="{{ route('pricing.calculator') }}"
        variant="default"
    />
@endsection