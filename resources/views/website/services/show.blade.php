{{-- resources/views/website/services/show.blade.php --}}
@extends('layouts.website')

@section('title', $service->name ?? 'Service Details - SaaS Platform')

@section('content')
    <!-- Service Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                <a href="{{ route('website.services.index') }}" class="hover:text-indigo-600">← Back to Services</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <span class="inline-block px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">
                        Service
                    </span>
                    <h1 class="mt-4 text-4xl font-bold text-gray-900">{{ $service->name ?? 'Service Name' }}</h1>
                    <p class="mt-4 text-lg text-gray-600">{{ $service->description ?? 'Comprehensive service description goes here.' }}</p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <span class="text-gray-600">Expert team with years of experience</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <span class="text-gray-600">Customized solutions for your business</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <span class="text-gray-600">Ongoing support and maintenance</span>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-4">
                        <x-website.btn-primary href="{{ route('website.contact') }}">
                            Get Started
                        </x-website.btn-primary>
                        <x-website.btn-secondary href="#">
                            Learn More
                        </x-website.btn-secondary>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-full h-64 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-cogs text-6xl text-indigo-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Service Details -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center">How We Help</h2>
            <div class="mt-12 space-y-8">
                <x-website.process-step 
                    number="01"
                    title="Discovery & Strategy"
                    description="We work with you to understand your business goals and create a comprehensive strategy."
                />
                <x-website.process-step 
                    number="02"
                    title="Design & Development"
                    description="Our team designs and develops your solution using the latest technologies."
                />
                <x-website.process-step 
                    number="03"
                    title="Launch & Optimization"
                    description="We help you launch successfully and continuously optimize for better results."
                />
            </div>
        </div>
    </section>
    
    <!-- Related Services -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Related Services"
                subtitle="Explore other services that might interest you."
            />
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-website.service-card 
                    icon="fa-cloud"
                    title="Cloud Migration"
                    description="Seamlessly migrate your applications to the cloud."
                    link="{{ route('website.services.show', 'cloud-migration') }}"
                    compact="true"
                />
                <x-website.service-card 
                    icon="fa-robot"
                    title="AI Integration"
                    description="Integrate AI capabilities into your products."
                    link="{{ route('website.services.show', 'ai-integration') }}"
                    compact="true"
                />
                <x-website.service-card 
                    icon="fa-shield-alt"
                    title="Security Consulting"
                    description="Ensure your product meets security standards."
                    link="{{ route('website.services.show', 'security-consulting') }}"
                    compact="true"
                />
            </div>
        </div>
    </section>
@endsection