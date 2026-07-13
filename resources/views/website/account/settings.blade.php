{{-- resources/views/website/account/settings.blade.php --}}
@extends('layouts.website')

@section('title', 'Settings - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-600">Manage your account settings and preferences.</p>
            </div>
            
            <!-- Account Navigation -->
            <x-website.account-nav />
            
            <!-- Settings Sections -->
            <div class="mt-8 space-y-6">
                <!-- General Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                            <select class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option>America/New York</option>
                                <option>America/Los Angeles</option>
                                <option>Europe/London</option>
                                <option>Asia/Tokyo</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <x-website.toggle-setting 
                            label="Email Notifications"
                            description="Receive email updates about your account activity."
                            checked="true"
                        />
                        <x-website.toggle-setting 
                            label="Product Updates"
                            description="Get notified about new features and product improvements."
                            checked="true"
                        />
                        <x-website.toggle-setting 
                            label="Marketing Communications"
                            description="Receive tips, resources, and promotional content."
                            checked="false"
                        />
                    </div>
                </div>
                
                <!-- Danger Zone -->
                <div class="bg-red-50 rounded-xl border border-red-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-red-700">Danger Zone</h3>
                        <p class="text-sm text-red-600 mt-1">Actions here are irreversible.</p>
                        <div class="mt-4 flex gap-4">
                            <button class="px-4 py-2 text-sm font-medium text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition">
                                Deactivate Account
                            </button>
                            <button class="px-4 py-2 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection