{{-- resources/views/frontend/pricing/compare.blade.php --}}
@php
    $pageTitle = 'Compare Plans - AgencyPro';
    $pageDescription = 'Compare all our pricing plans side by side to find the perfect fit for your business.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Compare Plans"
        subtitle="Find Your Perfect Fit"
        :breadcrumb="[
            ['label' => 'Pricing', 'url' => route('pricing.index')],
            ['label' => 'Compare']
        ]"
    />

    {{-- Comparison Table --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Compare Features</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Side-by-Side Comparison</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">See exactly what each plan includes and choose the one that's right for you.</p>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-lg">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#6c5ce7]/5 to-[#a78bfa]/5 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#4a4a4a] uppercase tracking-wider">Feature</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-[#0a0a0a]">Starter</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-[#6c5ce7] bg-[#6c5ce7]/5">Professional</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-[#0a0a0a]">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{-- Price --}}
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Price</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-bold text-[#0a0a0a]">$29</span>
                                <span class="text-xs text-[#4a4a4a]">/mo</span>
                            </td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5">
                                <span class="text-lg font-bold text-[#6c5ce7]">$99</span>
                                <span class="text-xs text-[#4a4a4a]">/mo</span>
                                <span class="inline-block ml-2 px-2 py-0.5 bg-[#6c5ce7] text-white text-xs rounded-full">Best Value</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-bold text-[#0a0a0a]">$299</span>
                                <span class="text-xs text-[#4a4a4a]">/mo</span>
                            </td>
                        </tr>

                        {{-- Features --}}
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Projects</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">1</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">5</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">Unlimited</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Storage</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">5 GB</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">50 GB</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">500 GB</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Support</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">Basic</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">Priority</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">24/7 Dedicated</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Analytics</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[#4a4a4a]">✓</span>
                            </td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5">
                                <span class="text-[#6c5ce7]">✓ Advanced</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[#4a4a4a]">✓ Custom</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">API Access</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">✓</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">✓ Full Access</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Team Collaboration</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">✓</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">✓ Unlimited</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Security Features</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">Basic</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">Advanced</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">Enterprise</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">SLA Guarantee</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">✓ 99.9%</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Training & Onboarding</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">✓</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#0a0a0a]">Custom Development</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-[#4a4a4a]">—</td>
                            <td class="px-6 py-4 text-center text-[#4a4a4a]">✓</td>
                        </tr>
                        <tr class="border-t-2 border-gray-200">
                            <td class="px-6 py-4 font-bold text-[#0a0a0a]">Best For</td>
                            <td class="px-6 py-4 text-center text-sm text-[#4a4a4a]">Startups</td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5 text-sm text-[#6c5ce7] font-semibold">Growing Teams</td>
                            <td class="px-6 py-4 text-center text-sm text-[#4a4a4a]">Enterprises</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4 text-center">
                                <a href="#contact" class="inline-block w-full px-4 py-2.5 bg-gray-100 text-[#0a0a0a] rounded-xl hover:bg-[#6c5ce7] hover:text-white transition-colors text-sm font-medium">
                                    Get Started
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center bg-[#6c5ce7]/5">
                                <a href="#contact" class="inline-block w-full px-4 py-2.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30 text-sm font-medium">
                                    Choose Professional
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#contact" class="inline-block w-full px-4 py-2.5 bg-gray-100 text-[#0a0a0a] rounded-xl hover:bg-[#6c5ce7] hover:text-white transition-colors text-sm font-medium">
                                    Contact Sales
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Note --}}
            <p class="text-center text-sm text-[#4a4a4a] mt-6">
                * All plans include a 14-day free trial. No credit card required.
            </p>
        </div>
    </section>

    {{-- CTA --}}
    <x-sections.cta 
        title="Still Not Sure Which Plan Is Right for You?"
        subtitle="We're Here to Help"
        description="Contact our sales team for a personalized consultation and recommendation."
        primary-text="Contact Sales"
        primary-link="#contact"
        secondary-text="Calculate Your Savings"
        secondary-link="{{ route('pricing.calculator') }}"
        variant="default"
    />
@endsection