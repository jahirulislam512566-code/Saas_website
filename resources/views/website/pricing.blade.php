{{-- resources/views/website/pricing.blade.php --}}
@extends('layouts.website')

@section('title', 'Pricing - SaaS Platform')

@section('content')
    <!-- Pricing Header -->
    <x-website.hero-section 
        title="Simple, Transparent Pricing"
        subtitle="Choose the plan that's right for your business. All plans include a 14-day free trial."
        cta_text="Start Free Trial"
        cta_link="{{ route('register') }}"
        secondary_text="View Features"
        secondary_link="{{ route('website.features') }}"
        size="small"
    />
    
    <!-- Pricing Plans -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <x-website.pricing-card 
                    name="Starter"
                    price="$29"
                    period="/month"
                    description="Perfect for small teams getting started."
                    :features="[
                        'Up to 100 users',
                        '10GB storage',
                        'Basic analytics',
                        'Email support'
                    ]"
                    cta_text="Start Free Trial"
                    cta_link="{{ route('register') }}"
                />
                
                <!-- Pro Plan -->
                <x-website.pricing-card 
                    name="Pro"
                    price="$79"
                    period="/month"
                    description="Most popular for growing businesses."
                    :features="[
                        'Unlimited users',
                        '100GB storage',
                        'Advanced analytics',
                        'Priority support',
                        'Custom integrations'
                    ]"
                    cta_text="Start Free Trial"
                    cta_link="{{ route('register') }}"
                    popular="true"
                />
                
                <!-- Enterprise Plan -->
                <x-website.pricing-card 
                    name="Enterprise"
                    price="$199"
                    period="/month"
                    description="For large organizations with complex needs."
                    :features="[
                        'Unlimited everything',
                        '1TB storage',
                        'Custom analytics',
                        '24/7 phone support',
                        'Dedicated account manager'
                    ]"
                    cta_text="Contact Sales"
                    cta_link="{{ route('website.contact') }}"
                />
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Frequently Asked Questions"
                subtitle="Everything you need to know about our pricing and plans."
            />
            
            <div class="space-y-4 mt-8">
                <x-website.faq-item 
                    question="Can I change plans later?"
                    answer="Yes, you can upgrade or downgrade your plan at any time. Changes will be prorated."
                />
                <x-website.faq-item 
                    question="What payment methods do you accept?"
                    answer="We accept all major credit cards, PayPal, and bank transfers for enterprise plans."
                />
                <x-website.faq-item 
                    question="Is there a free trial?"
                    answer="Yes, all plans come with a 14-day free trial. No credit card required."
                />
                <x-website.faq-item 
                    question="Can I cancel my subscription anytime?"
                    answer="Absolutely. You can cancel your subscription at any time with no hidden fees."
                />
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <x-website.cta-section 
        title="Ready to Get Started?"
        subtitle="Join thousands of businesses already using our platform."
        cta_text="Start Your Free Trial"
        cta_link="{{ route('register') }}"
        variant="dark"
    />
@endsection