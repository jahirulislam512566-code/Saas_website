{{-- resources/views/website/contact.blade.php --}}
@extends('layouts.website')

@section('title', 'Contact Us - SaaS Platform')

@section('content')
    <!-- Contact Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900">Get in Touch</h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Have questions? We'd love to hear from you. Our team is here to help.
            </p>
        </div>
    </section>
    
    <!-- Contact Form & Info -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div class="lg:col-span-1 space-y-8">
                    <x-website.contact-info 
                        icon="fa-envelope"
                        title="Email"
                        detail="support@saashub.com"
                    />
                    <x-website.contact-info 
                        icon="fa-phone"
                        title="Phone"
                        detail="+1 (555) 123-4567"
                    />
                    <x-website.contact-info 
                        icon="fa-map-marker-alt"
                        title="Location"
                        detail="123 SaaS Street, San Francisco, CA 94105"
                    />
                    <x-website.contact-info 
                        icon="fa-clock"
                        title="Hours"
                        detail="Mon-Fri: 9:00 AM - 6:00 PM EST"
                    />
                    
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Follow Us</h3>
                        <div class="flex gap-4 mt-4">
                            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-twitter text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-linkedin text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-github text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-youtube text-xl"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <x-website.contact-form />
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="py-0">
        <div class="h-96 bg-gray-200 flex items-center justify-center">
            <p class="text-gray-500">Interactive Map Here</p>
        </div>
    </section>
@endsection