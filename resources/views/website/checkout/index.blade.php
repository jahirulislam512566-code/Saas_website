{{-- resources/views/website/checkout/index.blade.php --}}
@extends('layouts.website')

@section('title', 'Checkout - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Checkout Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                <p class="text-gray-600">Complete your subscription purchase.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <!-- Order Summary -->
                <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pro Plan</span>
                            <span class="text-gray-900 font-medium">$79.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Additional Storage</span>
                            <span class="text-gray-900 font-medium">$20.00</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900 font-medium">$99.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (8%)</span>
                                <span class="text-gray-900 font-medium">$7.92</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between text-base font-bold">
                            <span class="text-gray-900">Total</span>
                            <span class="text-gray-900">$106.92</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-4">
                            <p>Included: 14-day free trial</p>
                            <p>Billed monthly</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Form -->
                <div class="md:col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                    
                    <form class="space-y-6">
                        <!-- Card Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                            <input type="text" placeholder="1234 5678 9012 3456" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                <input type="text" placeholder="MM/YY" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVC</label>
                                <input type="text" placeholder="123" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Billing Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                            <input type="text" placeholder="123 Main St" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" placeholder="San Francisco" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                                <input type="text" placeholder="94105" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Promo Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Promo Code</label>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Enter promo code" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <button type="button" class="px-4 py-2 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                                    Apply
                                </button>
                            </div>
                        </div>
                        
                        <!-- Terms -->
                        <div class="flex items-start gap-2">
                            <input type="checkbox" id="terms" class="mt-1">
                            <label for="terms" class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-800">Terms of Service</a> and 
                                <a href="#" class="text-indigo-600 hover:text-indigo-800">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <!-- Payment Button -->
                        <div class="pt-4">
                            <x-website.btn-primary class="w-full justify-center py-3 text-base">
                                <i class="fas fa-lock mr-2"></i> Subscribe & Pay $106.92
                            </x-website.btn-primary>
                            <p class="text-center text-xs text-gray-500 mt-3">
                                <i class="fas fa-shield-alt mr-1"></i> Your payment is secure and encrypted
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection