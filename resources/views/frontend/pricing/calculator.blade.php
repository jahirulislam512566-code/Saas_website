{{-- resources/views/frontend/pricing/calculator.blade.php --}}
@php
    $pageTitle = 'Pricing Calculator - AgencyPro';
    $pageDescription = 'Calculate your estimated costs and savings with our interactive pricing calculator.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Pricing Calculator"
        subtitle="Estimate Your Costs"
        :breadcrumb="[
            ['label' => 'Pricing', 'url' => route('pricing.index')],
            ['label' => 'Calculator']
        ]"
    />

    {{-- Calculator --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Interactive Tool</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Calculate Your Custom Pricing</h2>
                    <p class="mt-4 text-lg text-[#4a4a4a]">Adjust the sliders below to estimate your monthly costs and savings.</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-8 md:p-12" x-data="pricingCalculator()">
                    {{-- Number of Projects --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-[#0a0a0a]">Number of Projects</label>
                            <span class="text-sm font-bold text-[#6c5ce7]" x-text="projects"></span>
                        </div>
                        <input type="range" min="1" max="50" value="5" 
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#6c5ce7]"
                               x-model="projects">
                    </div>

                    {{-- Team Members --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-[#0a0a0a]">Team Members</label>
                            <span class="text-sm font-bold text-[#6c5ce7]" x-text="team"></span>
                        </div>
                        <input type="range" min="1" max="20" value="3" 
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#6c5ce7]"
                               x-model="team">
                    </div>

                    {{-- Storage --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-[#0a0a0a]">Storage (GB)</label>
                            <span class="text-sm font-bold text-[#6c5ce7]" x-text="storage"></span>
                        </div>
                        <input type="range" min="5" max="500" value="50" 
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#6c5ce7]"
                               x-model="storage">
                    </div>

                    {{-- Features --}}
                    <div class="mb-8">
                        <span class="text-sm font-medium text-[#0a0a0a] block mb-3">Additional Features</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl cursor-pointer hover:shadow-md transition-shadow">
                                <input type="checkbox" class="w-4 h-4 text-[#6c5ce7] rounded focus:ring-[#6c5ce7]" x-model="advancedAnalytics">
                                <span class="text-sm text-[#0a0a0a]">Advanced Analytics</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl cursor-pointer hover:shadow-md transition-shadow">
                                <input type="checkbox" class="w-4 h-4 text-[#6c5ce7] rounded focus:ring-[#6c5ce7]" x-model="apiAccess">
                                <span class="text-sm text-[#0a0a0a]">API Access</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl cursor-pointer hover:shadow-md transition-shadow">
                                <input type="checkbox" class="w-4 h-4 text-[#6c5ce7] rounded focus:ring-[#6c5ce7]" x-model="prioritySupport">
                                <span class="text-sm text-[#0a0a0a]">Priority Support</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl cursor-pointer hover:shadow-md transition-shadow">
                                <input type="checkbox" class="w-4 h-4 text-[#6c5ce7] rounded focus:ring-[#6c5ce7]" x-model="customDevelopment">
                                <span class="text-sm text-[#0a0a0a]">Custom Development</span>
                            </label>
                        </div>
                    </div>

                    {{-- Results --}}
                    <div class="bg-white rounded-2xl p-6 border-2 border-[#6c5ce7]/20">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <span class="text-sm text-[#4a4a4a]">Monthly Estimate</span>
                                <p class="text-3xl font-bold text-[#6c5ce7]" x-text="'$' + monthlyTotal"></p>
                            </div>
                            <div>
                                <span class="text-sm text-[#4a4a4a]">Yearly Estimate</span>
                                <p class="text-3xl font-bold text-[#0a0a0a]" x-text="'$' + yearlyTotal"></p>
                            </div>
                            <div>
                                <span class="text-sm text-[#4a4a4a]">You Save</span>
                                <p class="text-3xl font-bold text-emerald-500" x-text="'$' + yearlySavings + '/year'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap gap-4 mt-8 justify-center">
                        <a href="#contact" class="inline-flex items-center px-8 py-3.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30 font-medium">
                            Get This Quote
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <button class="inline-flex items-center px-8 py-3.5 bg-white border-2 border-gray-200 text-[#0a0a0a] rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300 font-medium">
                            Start Free Trial
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function pricingCalculator() {
            return {
                projects: 5,
                team: 3,
                storage: 50,
                advancedAnalytics: false,
                apiAccess: false,
                prioritySupport: false,
                customDevelopment: false,
                get monthlyTotal() {
                    let base = 29;
                    let projectCost = this.projects * 10;
                    let teamCost = this.team * 15;
                    let storageCost = Math.ceil(this.storage / 50) * 5;
                    let featuresCost = 0;
                    if (this.advancedAnalytics) featuresCost += 20;
                    if (this.apiAccess) featuresCost += 15;
                    if (this.prioritySupport) featuresCost += 25;
                    if (this.customDevelopment) featuresCost += 50;
                    return base + projectCost + teamCost + storageCost + featuresCost;
                },
                get yearlyTotal() {
                    return Math.round(this.monthlyTotal * 12 * 0.8);
                },
                get yearlySavings() {
                    return Math.round(this.monthlyTotal * 12 * 0.2);
                }
            }
        }
    </script>

    {{-- CTA --}}
    <x-sections.cta 
        title="Ready to Get Started?"
        subtitle="Let's Build Together"
        description="Contact us today and let's turn your estimate into a reality."
        primary-text="Contact Sales"
        primary-link="#contact"
        secondary-text="View All Plans"
        secondary-link="{{ route('pricing.index') }}"
        variant="default"
    />
@endsection