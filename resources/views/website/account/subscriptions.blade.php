{{-- resources/views/website/account/subscriptions.blade.php --}}
@extends('layouts.website')

@section('title', 'Subscriptions - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Subscriptions</h1>
                <p class="text-gray-600">Manage your subscriptions and billing.</p>
            </div>
            
            <!-- Account Navigation -->
            <x-website.account-nav />
            
            <!-- Current Plan -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Current Plan</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-block px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">Pro Plan</span>
                            <h4 class="mt-2 text-2xl font-bold text-gray-900">$79/month</h4>
                            <p class="text-sm text-gray-500">Billed monthly • Next payment on Dec 1, 2024</p>
                        </div>
                        <div class="flex gap-2">
                            <x-website.btn-secondary href="{{ route('website.subscription.plans') }}">
                                Change Plan
                            </x-website.btn-secondary>
                            <x-website.btn-primary href="{{ route('website.checkout.index') }}">
                                Upgrade
                            </x-website.btn-primary>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Plan Details -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-website.subscription-feature 
                    icon="fa-check-circle"
                    title="Active Features"
                    :features="['Unlimited Users', '100GB Storage', 'Advanced Analytics', 'Priority Support']"
                />
                <x-website.subscription-feature 
                    icon="fa-clock"
                    title="Billing Details"
                    :features="['Monthly Billing', 'Next Payment: Dec 1', 'Payment Method: ****4242']"
                />
                <x-website.subscription-feature 
                    icon="fa-history"
                    title="Usage Summary"
                    :features="['API Calls: 45,782/100K', 'Storage: 32GB/100GB', 'Users: 12/Unlimited']"
                />
            </div>
        </div>
    </div>
@endsection