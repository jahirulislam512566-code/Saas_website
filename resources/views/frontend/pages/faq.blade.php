{{-- resources/views/frontend/pages/faq.blade.php --}}
@php
    $pageTitle = 'FAQ - AgencyPro';
    $pageDescription = 'Find answers to frequently asked questions about our services, pricing, and processes.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="FAQPage" :data="[
        'faqs' => [
            [
                'question' => 'What services do you offer?',
                'answer' => 'We offer a comprehensive range of digital services including UI/UX design, web development, mobile app development, digital strategy, SEO and marketing, and cybersecurity solutions.'
            ],
            [
                'question' => 'How long does a typical project take?',
                'answer' => 'Project timelines vary depending on the scope and complexity. A typical website redesign might take 6-12 weeks, while a custom application development project could take 3-6 months.'
            ],
            [
                'question' => 'What is your pricing model?',
                'answer' => 'We offer flexible pricing models including fixed-price projects, hourly rates, and retainer arrangements. We work with you to find the best option that fits your budget.'
            ]
        ]
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Frequently Asked Questions"
        subtitle="FAQ"
        :breadcrumb="[['label' => 'FAQ']]"
    />

    {{-- FAQ Sections --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                {{-- General Questions --}}
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6">General Questions</h2>
                    <div class="space-y-4">
                        <x-ui.accordion>
                            <x-ui.accordion-item index="0" title="What services do you offer?">
                                We offer a comprehensive range of digital services including UI/UX design, web development, mobile app development, digital strategy, SEO and marketing, and cybersecurity solutions. Our team of experts can help you at any stage of your digital journey.
                            </x-ui.accordion-item>
                            <x-ui.accordion-item index="1" title="What industries do you work with?">
                                We work with clients across various industries including technology, healthcare, finance, e-commerce, education, non-profits, and more. Our diverse experience allows us to bring best practices from different sectors to your project.
                            </x-ui.accordion-item>
                            <x-ui.accordion-item index="2" title="Do you offer ongoing support?">
                                Yes, we offer various support and maintenance packages to ensure your digital assets remain secure, up-to-date, and performing optimally. Our support plans include regular updates, security patches, and priority assistance.
                            </x-ui.accordion-item>
                        </x-ui.accordion>
                    </div>
                </div>

                {{-- Pricing Questions --}}
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6">Pricing Questions</h2>
                    <div class="space-y-4">
                        <x-ui.accordion>
                            <x-ui.accordion-item index="3" title="What is your pricing model?">
                                We offer flexible pricing models including fixed-price projects, hourly rates, and retainer arrangements. We work with you to find the best option that fits your budget and project requirements. Contact us for a customized quote.
                            </x-ui.accordion-item>
                            <x-ui.accordion-item index="4" title="Do you offer discounts for startups?">
                                Yes, we offer special pricing and packages for startups and early-stage companies. We understand the budget constraints of new businesses and work to provide flexible solutions that support growth.
                            </x-ui.accordion-item>
                            <x-ui.accordion-item index="5" title="What payment methods do you accept?">
                                We accept all major credit cards, PayPal, and bank transfers. For larger projects, we also offer milestone-based payment plans.
                            </x-ui.accordion-item>
                        </x-ui.accordion>
                    </div>
                </div>

                {{-- Project Questions --}}
                <div>
                    <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6">Project Questions</h2>
                    <div class="space-y-4">
                        <x-ui.accordion>
                            <x-ui.accordion-item index="6" title="How long does a typical project take?">
                                Project timelines vary depending on the scope and complexity. A typical website redesign might take 6-12 weeks, while a custom application development project could take 3-6 months. We provide detailed timelines during our initial consultation.
                            </x-ui.accordion-item>
                            <x-ui.accordion-item index="7" title="How do you handle project management?">
                                We use agile project management methodologies and tools like Jira, Trello, and Slack to ensure transparent communication and efficient project delivery. You'll have a dedicated project manager and regular status updates.
                            </x-ui.accordion-item>
                            <x-ui.accordion-item index="8" title="Do you sign NDAs?">
                                Yes, we regularly sign Non-Disclosure Agreements and are happy to work with your legal team to ensure your intellectual property and confidential information are protected.
                            </x-ui.accordion-item>
                        </x-ui.accordion>
                    </div>
                </div>

                {{-- Still Have Questions --}}
                <div class="mt-16 text-center bg-gradient-to-br from-[#f8f7ff] to-white rounded-2xl p-12 border border-[#6c5ce7]/10">
                    <h3 class="text-2xl font-bold text-[#0a0a0a]">Still Have Questions?</h3>
                    <p class="text-[#4a4a4a] mt-2">We're here to help. Contact us and we'll get back to you within 24 hours.</p>
                    <div class="flex flex-wrap justify-center gap-4 mt-6">
                        <a href="{{ route('contact.index') }}" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                            Contact Us
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('contact.quote') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors">
                            Request a Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection