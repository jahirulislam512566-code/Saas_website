{{-- resources/views/website/home.blade.php --}}
@extends('layouts.website')

@section('title', 'Home - SaaS Platform')

@section('content')
    <!-- Hero Section -->
    <x-website.hero-section 
        title="Build Your SaaS Product Faster"
        subtitle="Everything you need to launch, scale, and grow your SaaS business. From billing to analytics, we've got you covered."
        cta_text="Start Free Trial"
        cta_link="{{ route('website.register') }}"
        secondary_text="View Features"
        secondary_link="{{ route('website.features') }}"
    >
        <x-slot name="badge">
            <span class="px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">
                🚀 New: AI-Powered Analytics
            </span>
        </x-slot>
        
        <x-slot name="stats">
            <div class="grid grid-cols-3 gap-8">
                <div>
                    <p class="text-2xl font-bold text-gray-900">10K+</p>
                    <p class="text-sm text-gray-600">Active Users</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">99.9%</p>
                    <p class="text-sm text-gray-600">Uptime</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">4.9⭐</p>
                    <p class="text-sm text-gray-600">User Rating</p>
                </div>
            </div>
        </x-slot>
    </x-website.hero-section>
    
    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Everything You Need to Succeed"
                subtitle="From powerful features to seamless integrations, we provide the tools to build your dream product."
            />
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-website.feature-card 
                    icon="fa-bolt"
                    title="Lightning Fast"
                    description="Optimized for speed with edge caching and CDN distribution worldwide."
                />
                <x-website.feature-card 
                    icon="fa-shield-alt"
                    title="Secure by Default"
                    description="Enterprise-grade security with SOC2 compliance and end-to-end encryption."
                />
                <x-website.feature-card 
                    icon="fa-chart-line"
                    title="Real-time Analytics"
                    description="Track user behavior, revenue, and growth metrics in real-time."
                />
                <x-website.feature-card 
                    icon="fa-cogs"
                    title="Automated Workflows"
                    description="Save time with powerful automation and integration capabilities."
                />
                <x-website.feature-card 
                    icon="fa-users"
                    title="Team Collaboration"
                    description="Built-in collaboration tools for teams of any size."
                />
                <x-website.feature-card 
                    icon="fa-mobile-alt"
                    title="Mobile Optimized"
                    description="Fully responsive design that works on any device."
                />
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="What Our Customers Say"
                subtitle="Join thousands of satisfied customers who trust our platform."
            />
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-website.testimonial-card 
                    name="Sarah Johnson"
                    role="CEO, TechStart"
                    avatar="SJ"
                    quote="This platform has revolutionized our workflow. We've seen a 300% increase in productivity since switching."
                />
                <x-website.testimonial-card 
                    name="Michael Chen"
                    role="CTO, CloudNine"
                    avatar="MC"
                    quote="The best SaaS platform we've ever used. The features are incredible and the support team is outstanding."
                />
                <x-website.testimonial-card 
                    name="Emily Rodriguez"
                    role="Product Manager, InnovateCo"
                    avatar="ER"
                    quote="I can't imagine running our business without this tool. It's simply essential for our daily operations."
                />
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <x-website.cta-section 
        title="Ready to Transform Your Business?"
        subtitle="Join thousands of satisfied customers and start building your SaaS product today."
        cta_text="Get Started for Free"
        cta_link="{{ route('website.register') }}"
    />
@endsection