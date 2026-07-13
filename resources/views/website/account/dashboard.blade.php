{{-- resources/views/website/account/dashboard.blade.php --}}
@extends('layouts.website')

@section('title', 'Dashboard - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600">Welcome back, {{ auth()->user()->name ?? 'User' }}!</p>
            </div>
            
            <!-- Account Navigation -->
            <x-website.account-nav />
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <x-website.stat-card 
                    number="1,234"
                    label="Total Users"
                    icon="fa-users"
                    change="+12%"
                    change-type="positive"
                />
                <x-website.stat-card 
                    number="$8,592"
                    label="Monthly Revenue"
                    icon="fa-dollar-sign"
                    change="+8%"
                    change-type="positive"
                />
                <x-website.stat-card 
                    number="342"
                    label="Active Subscriptions"
                    icon="fa-crown"
                    change="87% retention"
                    change-type="neutral"
                />
                <x-website.stat-card 
                    number="2.4%"
                    label="Churn Rate"
                    icon="fa-chart-line"
                    change="-0.3%"
                    change-type="positive"
                />
            </div>
            
            <!-- Recent Activity -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @for($i = 0; $i < 5; $i++)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">New user registered</p>
                                    <p class="text-xs text-gray-500">{{ $i + 1 }} minutes ago</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">#12345</span>
                        </div>
                    @endfor
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-website.quick-action 
                    icon="fa-user-plus"
                    title="Invite Team Members"
                    description="Add new team members to your account"
                    link="#"
                />
                <x-website.quick-action 
                    icon="fa-file-invoice"
                    title="View Invoices"
                    description="Download or view your invoices"
                    link="{{ route('website.account.invoices') }}"
                />
                <x-website.quick-action 
                    icon="fa-cog"
                    title="Account Settings"
                    description="Update your account preferences"
                    link="{{ route('website.account.settings') }}"
                />
            </div>
        </div>
    </div>
@endsection