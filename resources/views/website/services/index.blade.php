{{-- resources/views/website/services/index.blade.php --}}
@extends('layouts.website')

@section('title', 'Services - SaaS Platform')

@section('content')
    <!-- Services Header -->
    <x-website.hero-section 
        title="Our Services"
        subtitle="Comprehensive SaaS solutions designed to help your business thrive."
        cta_text="Get Started"
        cta_link="{{ route('register') }}"
        secondary_text="Contact Us"
        secondary_link="{{ route('website.contact') }}"
    />
    
    <!-- Services Grid -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="What We Offer"
                subtitle="Expert services to help you build, launch, and scale your SaaS product."
            />
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-website.service-card 
                    icon="fa-code"
                    title="Custom Development"
                    description="Build custom SaaS applications tailored to your specific needs."
                    link="{{ route('website.services.show', 'custom-development') }}"
                />
                <x-website.service-card 
                    icon="fa-cloud"
                    title="Cloud Migration"
                    description="Seamlessly migrate your existing applications to the cloud."
                    link="{{ route('website.services.show', 'cloud-migration') }}"
                />
                <x-website.service-card 
                    icon="fa-robot"
                    title="AI Integration"
                    description="Integrate cutting-edge AI capabilities into your products."
                    link="{{ route('website.services.show', 'ai-integration') }}"
                />
                <x-website.service-card 
                    icon="fa-mobile-alt"
                    title="Mobile Development"
                    description="Build native and cross-platform mobile applications."
                    link="{{ route('website.services.show', 'mobile-development') }}"
                />
                <x-website.service-card 
                    icon="fa-database"
                    title="Data Analytics"
                    description="Harness the power of your data with advanced analytics."
                    link="{{ route('website.services.show', 'data-analytics') }}"
                />
                <x-website.service-card 
                    icon="fa-shield-alt"
                    title="Security Consulting"
                    description="Ensure your SaaS product meets enterprise security standards."
                    link="{{ route('website.services.show', 'security-consulting') }}"
                />
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <x-website.cta-section 
        title="Need a Custom Solution?"
        subtitle="Let's discuss how we can help your business grow."
        cta_text="Contact Us"
        cta_link="{{ route('website.contact') }}"
        variant="dark"
    />
@endsection