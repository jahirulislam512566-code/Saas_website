{{-- resources/views/website/features.blade.php --}}
@extends('layouts.website')

@section('title', 'Features - SaaS Platform')
@section('meta_description', 'Discover powerful features for modern businesses including analytics, security, automation, and performance optimization tools.')

@section('content')
    {{-- Hero Section --}}
    <x-website.hero-section 
        title="Powerful Features for Modern Businesses"
        subtitle="Everything you need to build, scale, and grow your SaaS product."
        cta_text="Get Started"
        cta_link="{{ route('register') }}"
        secondary_text="View Pricing"
        secondary_link="{{ route('pricing') }}"
        gradient="from-primary-600 to-secondary-600"
    />

    {{-- Main Features Grid --}}
    <section class="py-20 bg-white" aria-label="Main features">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Everything You Need to Succeed"
                subtitle="Our platform is packed with features to help you build better products faster."
                alignment="center"
            />
            
            <div class="mt-12 space-y-16 lg:space-y-20">
                <x-website.feature-detail 
                    icon="fa-rocket"
                    title="Lightning Fast Performance"
                    description="Our platform is optimized for speed with CDN distribution and edge caching. Experience lightning-fast load times and seamless user experiences."
                    image="{{ asset('images/features/performance.svg') }}"
                    image_alt="Performance dashboard showing speed metrics"
                    animation="fade-right"
                />
                
                <x-website.feature-detail 
                    icon="fa-shield-alt"
                    title="Enterprise-Grade Security"
                    description="SOC2 compliant with end-to-end encryption and regular security audits. Your data is protected with enterprise-grade security measures."
                    image="{{ asset('images/features/security.svg') }}"
                    image_alt="Security shield with lock icon"
                    reverse="true"
                    animation="fade-left"
                />
                
                <x-website.feature-detail 
                    icon="fa-chart-line"
                    title="Real-Time Analytics"
                    description="Track user behavior, revenue, and growth metrics in real-time dashboards. Make data-driven decisions with comprehensive analytics."
                    image="{{ asset('images/features/analytics.svg') }}"
                    image_alt="Analytics dashboard with charts"
                    animation="fade-right"
                />
                
                <x-website.feature-detail 
                    icon="fa-cogs"
                    title="Powerful Automation"
                    description="Save time with automated workflows and integrations with your favorite tools. Streamline your processes and boost productivity."
                    image="{{ asset('images/features/automation.svg') }}"
                    image_alt="Automation workflow diagram"
                    reverse="true"
                    animation="fade-left"
                />
            </div>
        </div>
    </section>

    {{-- Feature Highlights Grid --}}
    <section class="py-20 bg-gray-50" aria-label="Feature highlights">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Why Choose SaaS Hub"
                subtitle="We're different from the rest. Here's why thousands of businesses trust us."
                alignment="center"
            />
            
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <x-website.highlight-card 
                    icon="fa-check-circle"
                    title="Easy to Use"
                    description="Intuitive interface that requires no training."
                    color="green"
                    animation="fade-up"
                />
                <x-website.highlight-card 
                    icon="fa-clock"
                    title="Fast Setup"
                    description="Get started in minutes, not days or weeks."
                    color="blue"
                    animation="fade-up"
                    delay="100"
                />
                <x-website.highlight-card 
                    icon="fa-headset"
                    title="24/7 Support"
                    description="Round-the-clock support whenever you need it."
                    color="purple"
                    animation="fade-up"
                    delay="200"
                />
                <x-website.highlight-card 
                    icon="fa-expand"
                    title="Scalable"
                    description="Grow from startup to enterprise seamlessly."
                    color="orange"
                    animation="fade-up"
                    delay="300"
                />
            </div>
        </div>
    </section>

    {{-- Additional Features Section --}}
    <section class="py-20 bg-white" aria-label="Additional features">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Advanced Capabilities"
                subtitle="Take your business to the next level with our premium features."
                alignment="center"
            />
            
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <x-website.feature-card 
                    icon="fa-database"
                    title="Data Export"
                    description="Export your data in multiple formats including CSV, JSON, and Excel."
                    badge="Popular"
                />
                <x-website.feature-card 
                    icon="fa-users"
                    title="Team Collaboration"
                    description="Invite team members and collaborate in real-time."
                />
                <x-website.feature-card 
                    icon="fa-puzzle-piece"
                    title="API Access"
                    description="Integrate with our RESTful API for custom solutions."
                    badge="New"
                />
                <x-website.feature-card 
                    icon="fa-mobile-alt"
                    title="Mobile Responsive"
                    description="Access your dashboard from any device, anywhere."
                />
                <x-website.feature-card 
                    icon="fa-clock"
                    title="Scheduled Reports"
                    description="Schedule automated reports delivered to your inbox."
                />
                <x-website.feature-card 
                    icon="fa-shield-virus"
                    title="GDPR Compliant"
                    description="Fully compliant with GDPR and other privacy regulations."
                />
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-20 bg-gray-50" aria-label="Customer testimonials">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Loved by Businesses Worldwide"
                subtitle="Hear what our customers have to say about their experience."
                alignment="center"
            />
            
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <x-website.testimonial-card 
                    quote="This platform has transformed how we operate. The features are exactly what we needed."
                    author="Sarah Johnson"
                    role="CEO, TechStart Inc"
                    avatar="{{ asset('images/avatars/sarah.jpg') }}"
                    rating="5"
                />
                <x-website.testimonial-card 
                    quote="The automation features alone saved us 20 hours per week. Highly recommended!"
                    author="Mike Chen"
                    role="CTO, CloudSolve"
                    avatar="{{ asset('images/avatars/mike.jpg') }}"
                    rating="5"
                />
                <x-website.testimonial-card 
                    quote="Excellent support team and powerful features. The best investment we've made."
                    author="Emily Rodriguez"
                    role="Product Manager, DataFlow"
                    avatar="{{ asset('images/avatars/emily.jpg') }}"
                    rating="5"
                />
            </div>
        </div>
    </section>

    {{-- Comparison Table --}}
    <section class="py-20 bg-white" aria-label="Feature comparison">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Compare Plans"
                subtitle="Find the perfect plan for your business needs."
                alignment="center"
            />
            
            <div class="mt-12">
                  <x-website.pricing-comparison/>
                {{-- With custom plans --}}
            {{-- <x-website.pricing-comparison 
            :plans="[
                  [
                        'name' => 'Basic',
                        'price' => 19,
                        'period' => 'month',
                        'description' => 'For individuals',
                        'cta_text' => 'Start Free',
                        'cta_link' => '/signup',
                        'features' => ['Feature 1', 'Feature 2']
                  ],
                  // ... more plans
            ]"
            :features="['Feature 1', 'Feature 2', 'Feature 3']"
            currency="$"
            billingPeriod="monthly"
            /> --}}
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <x-website.cta-section 
        title="Ready to Transform Your Business?"
        subtitle="Start your free trial today and experience the difference."
        cta_text="Start Free Trial"
        cta_link="{{ route('register') }}"
        secondary_text="Schedule a Demo"
        secondary_link="{{ route('demo') }}"
        variant="gradient"
    />
@endsection

@push('styles')
    {{-- Add any page-specific CSS --}}
    <style>
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@push('scripts')
    {{-- Add any page-specific JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize animations or interactions
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            });
            
            document.querySelectorAll('.feature-card, .highlight-card').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
@endpush