{{-- resources/views/frontend/services/show.blade.php --}}
{{-- Generic service detail page that can be used for any service --}}
@props([
    'service' => null,
    'title' => 'Service Detail',
    'description' => 'Service description',
    'icon' => '🚀',
    'features' => [],
    'process' => [],
    'faqs' => [],
    'relatedServices' => [],
])

@extends('layouts.app')

@section('title', $title . ' - AgencyPro')
@section('description', $description)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        :title="$title"
        subtitle="Service Detail"
        :breadcrumb="[
            ['label' => 'Services', 'url' => route('services.index')],
            ['label' => $title]
        ]"
    />

    {{-- Service Overview --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
                <div>
                    <div class="w-20 h-20 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-5xl mb-6">
                        {{ $icon }}
                    </div>
                    <h2 class="text-3xl font-bold text-[#0a0a0a] mb-4">{{ $title }}</h2>
                    <p class="text-lg text-[#4a4a4a] leading-relaxed">{{ $description }}</p>
                    
                    @if(!empty($features))
                        <div class="mt-8 space-y-3">
                            @foreach($features as $feature)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-[#6c5ce7] flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-[#4a4a4a]">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="#contact" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                            Get a Quote
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('portfolio.index') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors">
                            View Our Work
                        </a>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-[#6c5ce7]/5 to-[#a78bfa]/5 rounded-2xl p-8 border border-[#6c5ce7]/10">
                    <div class="text-center">
                        <div class="text-8xl mb-4">{{ $icon }}</div>
                        <h3 class="text-2xl font-bold text-[#0a0a0a]">Why Choose Our {{ $title }}?</h3>
                        <ul class="mt-6 space-y-3 text-left">
                            <li class="flex items-start gap-3">
                                <span class="text-[#6c5ce7]">✓</span>
                                <span class="text-[#4a4a4a]">Expert team with proven experience</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-[#6c5ce7]">✓</span>
                                <span class="text-[#4a4a4a]">Customized solutions for your needs</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-[#6c5ce7]">✓</span>
                                <span class="text-[#4a4a4a]">Transparent pricing and process</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-[#6c5ce7]">✓</span>
                                <span class="text-[#4a4a4a]">Ongoing support and maintenance</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Process --}}
    @if(!empty($process))
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Our Process</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">How We Work</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                    @foreach($process as $step)
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto bg-[#6c5ce7]/10 rounded-full flex items-center justify-center text-2xl font-bold text-[#6c5ce7] mb-4">
                                {{ $step['number'] }}
                            </div>
                            <h4 class="font-bold text-[#0a0a0a]">{{ $step['title'] }}</h4>
                            <p class="text-sm text-[#4a4a4a] mt-1">{{ $step['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQs --}}
    @if(!empty($faqs))
        <x-sections.faq 
            title="Frequently Asked Questions"
            subtitle="FAQ"
            description="Find answers to common questions about our {{ $title }} services."
            :items="$faqs"
            columns="1"
        />
    @endif

    {{-- Related Services --}}
    @if(!empty($relatedServices))
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Related Services</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Explore More</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    @foreach($relatedServices as $related)
                        <a href="{{ $related['url'] }}" class="group bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 no-underline">
                            <div class="text-4xl mb-3">{{ $related['icon'] }}</div>
                            <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">{{ $related['title'] }}</h4>
                            <p class="text-sm text-[#4a4a4a] mt-1">{{ $related['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <x-sections.cta 
        title="Ready to Get Started with {{ $title }}?"
        subtitle="Let's Work Together"
        description="Contact us today and let's discuss how we can help you achieve your goals."
        primary-text="Get a Free Quote"
        primary-link="#contact"
        secondary-text="Learn More"
        secondary-link="#"
        variant="default"
    />
@endsection