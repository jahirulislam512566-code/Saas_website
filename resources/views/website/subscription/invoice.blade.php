{{-- resources/views/website/subscription/invoice.blade.php --}}
@extends('layouts.website')

@section('title', 'Invoice - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Invoice Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                        <p class="text-sm text-gray-500">#INV-{{ str_pad(rand(1, 9999), 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <!-- Invoice Details -->
                <div class="p-6">
                    <!-- Company Info -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">From</h3>
                            <p class="text-sm text-gray-600 mt-1">SaaS Hub Inc.</p>
                            <p class="text-sm text-gray-600">123 SaaS Street</p>
                            <p class="text-sm text-gray-600">San Francisco, CA 94105</p>
                            <p class="text-sm text-gray-600">support@saashub.com</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Bill To</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ auth()->user()->name ?? 'John Doe' }}</p>
                            <p class="text-sm text-gray-600">{{ auth()->user()->email ?? 'john@example.com' }}</p>
                            <p class="text-sm text-gray-600">+1 (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <!-- Invoice Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">Pro Plan - Monthly Subscription</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">$79.00</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">$79.00</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">Additional Storage (100GB)</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">$20.00</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">$20.00</td>
                                </tr>
                            </tbody>
                            <tfoot class="border-t-2 border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">$99.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Tax (8%)</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">$7.92</td>
                                </tr>
                                <tr class="border-t-2 border-gray-200">
                                    <td colspan="3" class="px-4 py-3 text-right text-base font-bold text-gray-900">Total</td>
                                    <td class="px-4 py-3 text-right text-base font-bold text-gray-900">$106.92</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-8 flex flex-wrap gap-4">
                        <x-website.btn-primary>
                            <i class="fas fa-print mr-2"></i> Print Invoice
                        </x-website.btn-primary>
                        <x-website.btn-secondary>
                            <i class="fas fa-download mr-2"></i> Download PDF
                        </x-website.btn-secondary>
                        <a href="{{ route('website.account.invoices') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 transition">
                            ← Back to Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection