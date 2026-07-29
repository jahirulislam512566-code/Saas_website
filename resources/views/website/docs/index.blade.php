{{-- resources/views/website/docs/index.blade.php --}}
@extends('layouts.website')

@section('title', 'Documentation - SaaS Platform')
@section('meta_description', 'Comprehensive documentation for our SaaS platform. Learn how to get started, integrate, and scale your applications.')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-r from-primary-600 to-secondary-600 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold">Documentation</h1>
                <p class="mt-4 text-xl text-primary-100">Everything you need to build, integrate, and scale with our platform</p>
                
                {{-- Search Bar --}}
                <div class="mt-8 max-w-2xl mx-auto">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search documentation..." 
                            class="w-full px-6 py-4 pl-14 text-gray-900 bg-white rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                            x-data="{}"
                            @keyup.enter="window.location.href = '/docs/search?q=' + $event.target.value"
                        >
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <kbd class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 text-sm">
                            ⌘K
                        </kbd>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="mt-6 flex flex-wrap justify-center gap-3 text-sm">
                    <a href="#getting-started" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition-colors">
                        Getting Started
                    </a>
                    <a href="#guides" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition-colors">
                        Guides
                    </a>
                    <a href="#api-reference" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition-colors">
                        API Reference
                    </a>
                    <a href="#faq" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition-colors">
                        FAQ
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Documentation Grid --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Sidebar Navigation --}}
                <aside class="lg:col-span-1">
                    <x-website.docs-sidebar />
                </aside>

                {{-- Main Content --}}
                <main class="lg:col-span-3">
                    {{-- Getting Started --}}
                    <section id="getting-started" class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="bg-primary-100 text-primary-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Getting Started
                        </h2>
                        <p class="mt-2 text-gray-600">Start building with our platform in minutes.</p>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-website.doc-card 
                                title="Quick Start Guide"
                                description="Set up your account and make your first API call"
                                icon="fa-rocket"
                                link="{{ route('docs.article', 'quick-start') }}"
                                color="blue"
                            />
                            <x-website.doc-card 
                                title="Installation"
                                description="Install our SDKs and dependencies"
                                icon="fa-download"
                                link="{{ route('docs.article', 'installation') }}"
                                color="green"
                            />
                            <x-website.doc-card 
                                title="Authentication"
                                description="Secure your application with API keys"
                                icon="fa-lock"
                                link="{{ route('docs.article', 'authentication') }}"
                                color="purple"
                            />
                            <x-website.doc-card 
                                title="First Project"
                                description="Create and deploy your first project"
                                icon="fa-folder-open"
                                link="{{ route('docs.article', 'first-project') }}"
                                color="orange"
                            />
                        </div>
                    </section>

                    {{-- Guides --}}
                    <section id="guides" class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </span>
                            Guides
                        </h2>
                        <p class="mt-2 text-gray-600">In-depth guides to help you master the platform.</p>
                        
                        <div class="mt-4 space-y-3">
                            <x-website.doc-list-item 
                                title="Building Your First Application"
                                description="Step-by-step tutorial to build a complete application"
                                category="Beginner"
                                link="{{ route('docs.article', 'building-first-app') }}"
                            />
                            <x-website.doc-list-item 
                                title="Advanced Configuration"
                                description="Fine-tune your application with advanced settings"
                                category="Advanced"
                                link="{{ route('docs.article', 'advanced-config') }}"
                            />
                            <x-website.doc-list-item 
                                title="Performance Optimization"
                                description="Optimize your application for maximum performance"
                                category="Performance"
                                link="{{ route('docs.article', 'performance-optimization') }}"
                            />
                            <x-website.doc-list-item 
                                title="Security Best Practices"
                                description="Keep your application secure with our best practices"
                                category="Security"
                                link="{{ route('docs.article', 'security-best-practices') }}"
                            />
                        </div>
                    </section>

                    {{-- API Reference --}}
                    <section id="api-reference" class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="bg-green-100 text-green-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            API Reference
                        </h2>
                        <p class="mt-2 text-gray-600">Complete API reference for developers.</p>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-website.api-card 
                                method="GET"
                                endpoint="/api/v1/users"
                                description="List all users"
                                link="{{ route('docs.article', 'api-users') }}"
                            />
                            <x-website.api-card 
                                method="POST"
                                endpoint="/api/v1/users"
                                description="Create a new user"
                                link="{{ route('docs.article', 'api-create-user') }}"
                            />
                            <x-website.api-card 
                                method="PUT"
                                endpoint="/api/v1/users/{id}"
                                description="Update a user"
                                link="{{ route('docs.article', 'api-update-user') }}"
                            />
                            <x-website.api-card 
                                method="DELETE"
                                endpoint="/api/v1/users/{id}"
                                description="Delete a user"
                                link="{{ route('docs.article', 'api-delete-user') }}"
                            />
                            <x-website.api-card 
                                method="GET"
                                endpoint="/api/v1/products"
                                description="List all products"
                                link="{{ route('docs.article', 'api-products') }}"
                            />
                            <x-website.api-card 
                                method="POST"
                                endpoint="/api/v1/products"
                                description="Create a product"
                                link="{{ route('docs.article', 'api-create-product') }}"
                            />
                        </div>
                    </section>

                    {{-- FAQ --}}
                    <section id="faq">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="bg-yellow-100 text-yellow-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Frequently Asked Questions
                        </h2>
                        
                        <div class="mt-4 space-y-4">
                            <x-website.faq-item 
                                question="How do I get started?"
                                answer="Sign up for a free account, verify your email, and follow our Quick Start Guide to make your first API call."
                            />
                            <x-website.faq-item 
                                question="What SDKs are available?"
                                answer="We offer SDKs for JavaScript, Python, Ruby, PHP, Java, and Go. Check our installation guide for more details."
                            />
                            <x-website.faq-item 
                                question="How does pricing work?"
                                answer="We offer flexible pricing plans based on usage. Visit our pricing page for detailed information."
                            />
                            <x-website.faq-item 
                                question="Is there a free tier?"
                                answer="Yes, we offer a generous free tier with 10,000 requests per month."
                            />
                        </div>
                    </section>

                    {{-- Helpful Links --}}
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Still need help?</p>
                                <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                    Contact Support →
                                </a>
                            </div>
                            <div class="flex gap-4">
                                <a href="#" class="text-gray-400 hover:text-gray-600">
                                    <span class="sr-only">GitHub</span>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.468-2.38 1.235-3.22-.123-.3-.535-1.52.117-3.16 0 0 1.008-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.29-1.552 3.297-1.23 3.297-1.23.653 1.64.24 2.86.118 3.16.768.84 1.233 1.91 1.233 3.22 0 4.61-2.804 5.62-5.476 5.92.43.37.824 1.102.824 2.22 0 1.602-.015 2.894-.015 3.287 0 .322.216.694.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-gray-600">
                                    <span class="sr-only">Stack Overflow</span>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.986 21.865v-6.404h2.134V24H1.844v-8.539h2.13v6.404h15.012zM6.111 19.731H16.85v-2.137H6.111v2.137zm.259-4.852l10.48 2.189.447-2.137-10.48-2.187-.447 2.135zm1.359-4.673l9.706 4.529.903-1.95-9.706-4.53-.903 1.951zm2.713-4.408l8.232 6.916 1.379-1.645-8.232-6.916-1.379 1.645zm4.572-4.038L18.465 6.69l1.456-1.556-7.541-7.015-1.456 1.556z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <x-website.cta-section 
        title="Ready to Build Something Amazing?"
        subtitle="Start building with our platform today. Get started with a free trial."
        cta_text="Start Free Trial"
        cta_link="{{ route('register') }}"
        secondary_text="View Examples"
        secondary_link="{{ route('docs.article', 'examples') }}"
        variant="gradient"
    />
@endsection

@push('styles')
<style>
    .doc-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .doc-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
</style>
@endpush