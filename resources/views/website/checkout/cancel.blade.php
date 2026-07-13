{{-- resources/views/website/checkout/cancel.blade.php --}}
@extends('layouts.website')

@section('title', 'Payment Cancelled - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
                <!-- Cancel Icon -->
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-times text-4xl text-amber-600"></i>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900">Payment Cancelled</h1>
                <p class="mt-4 text-lg text-gray-600">
                    Your payment was cancelled. No charges have been made to your account.
                </p>
                
                <div class="mt-8 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-amber-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        You can try again anytime. If you need assistance, please contact our support team.
                    </p>
                </div>
                
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <x-website.btn-primary href="{{ route('website.checkout.index') }}">
                        <i class="fas fa-redo mr-2"></i> Try Again
                    </x-website.btn-primary>
                    <x-website.btn-secondary href="{{ route('website.subscription.plans') }}">
                        <i class="fas fa-arrow-left mr-2"></i> View Plans
                    </x-website.btn-secondary>
                </div>
                
                <p class="mt-6 text-sm text-gray-500">
                    Need help? <a href="{{ route('website.contact') }}" class="text-indigo-600 hover:text-indigo-800">Contact our support team</a>
                </p>
            </div>
        </div>
    </div>
@endsection