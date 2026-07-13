{{-- resources/views/website/checkout/success.blade.php --}}
@extends('layouts.website')

@section('title', 'Payment Successful - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-4xl text-green-600"></i>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900">Payment Successful!</h1>
                <p class="mt-4 text-lg text-gray-600">
                    Your subscription has been confirmed. Welcome to SaaS Hub!
                </p>
                
                <div class="mt-8 bg-gray-50 rounded-lg p-6 text-left">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Order Number</span>
                        <span class="text-gray-900 font-medium">#INV-{{ str_pad(rand(1, 9999), 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500">Plan</span>
                        <span class="text-gray-900 font-medium">Pro Plan</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500">Amount</span>
                        <span class="text-gray-900 font-medium">$106.92</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500">Payment Method</span>
                        <span class="text-gray-900 font-medium">****4242</span>
                    </div>
                </div>
                
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <x-website.btn-primary href="{{ route('website.account.dashboard') }}">
                        <i class="fas fa-chart-pie mr-2"></i> Go to Dashboard
                    </x-website.btn-primary>
                    <x-website.btn-secondary href="{{ route('website.home') }}">
                        <i class="fas fa-home mr-2"></i> Return Home
                    </x-website.btn-secondary>
                </div>
                
                <p class="mt-6 text-sm text-gray-500">
                    A confirmation email has been sent to your email address.
                </p>
            </div>
        </div>
    </div>
@endsection