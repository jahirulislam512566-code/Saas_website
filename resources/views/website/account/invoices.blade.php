{{-- resources/views/website/account/invoices.blade.php --}}
@extends('layouts.website')

@section('title', 'Invoices - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Invoices</h1>
                <p class="text-gray-600">View and download your invoices.</p>
            </div>
            
            <!-- Account Navigation -->
            <x-website.account-nav />
            
            <!-- Invoices Table -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @for($i = 0; $i < 5; $i++)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#INV-{{ str_pad($i + 1, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">Dec {{ 15 - $i }}, 2024</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">$79.00</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium {{ $i === 0 ? 'text-green-600 bg-green-100' : 'text-gray-600 bg-gray-100' }} rounded-full">
                                            {{ $i === 0 ? 'Paid' : 'Paid' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                                            Download <i class="fas fa-download ml-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection