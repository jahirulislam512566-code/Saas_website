{{-- resources/views/website/subscription/plans.blade.php --}}
@extends('layouts.website')

@section('title', 'Subscription Plans - SaaS Platform')

@section('content')
    <!-- Plans Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900">Choose Your Plan</h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Select the plan that best fits your needs. All plans include a 14-day free trial.
            </p>
        </div>
    </section>
    
    <!-- Plans -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-website.pricing-card 
                    name="Starter"
                    price="$29"
                    period="/month"
                    description="Perfect for small teams."
                    :features="[
                        'Up to 100 users',
                        '10GB storage',
                        'Basic analytics',
                        'Email support'
                    ]"
                    cta_text="Choose Plan"
                    cta_link="{{ route('website.checkout.index', ['plan' => 'starter']) }}"
                />
                
                <x-website.pricing-card 
                    name="Pro"
                    price="$79"
                    period="/month"
                    description="Best for growing businesses."
                    :features="[
                        'Unlimited users',
                        '100GB storage',
                        'Advanced analytics',
                        'Priority support',
                        'Custom integrations'
                    ]"
                    cta_text="Choose Plan"
                    cta_link="{{ route('website.checkout.index', ['plan' => 'pro']) }}"
                    popular="true"
                />
                
                <x-website.pricing-card 
                    name="Enterprise"
                    price="$199"
                    period="/month"
                    description="For large organizations."
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
@endsection