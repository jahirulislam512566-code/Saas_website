{{-- resources/views/website/features.blade.php --}}
@extends('layouts.website')

@section('title', 'Features - SaaS Platform')

@section('content')
    <!-- Features Header -->
    <x-website.hero-section 
        title="Powerful Features for Modern Businesses"
        subtitle="Everything you need to build, scale, and grow your SaaS product."
        cta_text="Get Started"
        cta_link="{{ route('register') }}"
        secondary_text="View Pricing"
        secondary_link="{{ route('website.pricing') }}"
    />
    
    <!-- Main Features -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Everything You Need to Succeed"
                subtitle="Our platform is packed with features to help you build better products faster."
            />
            
            <div class="space-y-12">
                <x-website.feature-detail 
                    icon="fa-rocket"
                    title="Lightning Fast Performance"
                    description="Our platform is optimized for speed with CDN distribution and edge caching. Experience lightning-fast load times and seamless user experiences."
                />
                
                <x-website.feature-detail 
                    icon="fa-shield-alt"
                    title="Enterprise-Grade Security"
                    description="SOC2 compliant with end-to-end encryption and regular security audits. Your data is protected with enterprise-grade security measures."
                    reverse="true"
                />
                
                <x-website.feature-detail 
                    icon="fa-chart-line"
                    title="Real-Time Analytics"
                    description="Track user behavior, revenue, and growth metrics in real-time dashboards. Make data-driven decisions with comprehensive analytics."
                />
                
                <x-website.feature-detail 
                    icon="fa-cogs"
                    title="Powerful Automation"
                    description="Save time with automated workflows and integrations with your favorite tools. Streamline your processes and boost productivity."
                    reverse="true"
                />
            </div>
        </div>
    </section>
    
    <!-- Feature Highlights -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Why Choose SaaS Hub"
                subtitle="We're different from the rest. Here's why thousands of businesses trust us."
            />
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-website.highlight-card 
                    icon="fa-check-circle"
                    title="Easy to Use"
                    description="Intuitive interface that requires no training."
                />
                <x-website.highlight-card 
                    icon="fa-clock"
                    title="Fast Setup"
                    description="Get started in minutes, not days or weeks."
                />
                <x-website.highlight-card 
                    icon="fa-headset"
                    title="24/7 Support"
                    description="Round-the-clock support whenever you need it."
                />
                <x-website.highlight-card 
                    icon="fa-expand"
                    title="Scalable"
                    description="Grow from startup to enterprise seamlessly."
                />
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <x-website.cta-section 
        title="Ready to Get Started?"
        subtitle="Start your free trial today and see the difference."
        cta_text="Start Free Trial"
        cta_link="{{ route('register') }}"
    />
@endsection