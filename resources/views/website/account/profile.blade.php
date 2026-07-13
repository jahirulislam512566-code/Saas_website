{{-- resources/views/website/account/profile.blade.php --}}
@extends('layouts.website')

@section('title', 'Profile - SaaS Platform')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
                <p class="text-gray-600">Manage your account details and preferences.</p>
            </div>
            
            <!-- Account Navigation -->
            <x-website.account-nav />
            
            <!-- Profile Form -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                </div>
                <form class="p-6 space-y-6">
                    <!-- Avatar -->
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ auth()->user()->initials ?? 'U' }}
                        </div>
                        <div>
                            <button type="button" class="px-4 py-2 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                                Change Photo
                            </button>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF. Max 2MB.</p>
                        </div>
                    </div>
                    
                    <!-- Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" value="{{ auth()->user()->first_name ?? 'John' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" value="{{ auth()->user()->last_name ?? 'Doe' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" value="{{ auth()->user()->email ?? 'john@example.com' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Your email address is verified.</p>
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" value="{{ auth()->user()->phone ?? '+1 (555) 123-4567' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <!-- Save Button -->
                    <div class="pt-4">
                        <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection