{{-- resources/views/website/portfolio/show.blade.php --}}
@extends('layouts.website')

@section('title', $project->title ?? 'Portfolio Project - SaaS Platform')

@section('content')
    <!-- Project Header -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                <a href="{{ route('website.portfolio.index') }}" class="hover:text-indigo-600">← Back to Portfolio</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <span class="inline-block px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">
                        {{ $project->category->name ?? 'Project' }}
                    </span>
                    <h1 class="mt-4 text-4xl font-bold text-gray-900">{{ $project->title ?? 'Project Title' }}</h1>
                    <p class="mt-4 text-lg text-gray-600">{{ $project->description ?? 'Project description goes here.' }}</p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <span class="text-gray-600">Delivered on time and on budget</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <span class="text-gray-600">Exceeded client expectations</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <span class="text-gray-600">Ongoing support and maintenance</span>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-4">
                        <x-website.btn-primary href="{{ route('website.contact') }}">
                            Start Your Project
                        </x-website.btn-primary>
                        <x-website.btn-secondary href="#">
                            View Live Demo
                        </x-website.btn-secondary>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-full h-64 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-indigo-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Project Details -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center">Project Highlights</h2>
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-website.stat-card 
                    number="12"
                    label="Months"
                    icon="fa-clock"
                />
                <x-website.stat-card 
                    number="15+"
                    label="Team Members"
                    icon="fa-users"
                />
                <x-website.stat-card 
                    number="50K+"
                    label="Active Users"
                    icon="fa-users-cog"
                />
            </div>
        </div>
    </section>
    
    <!-- Testimonial -->
    @if($project->testimonial ?? false)
        <section class="py-16 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-website.testimonial-card 
                    name="{{ $project->client_name ?? 'Client Name' }}"
                    role="{{ $project->client_role ?? 'CEO' }}"
                    avatar="{{ $project->client_initials ?? 'CL' }}"
                    quote="{{ $project->testimonial ?? 'Amazing work! This project exceeded our expectations.' }}"
                    large="true"
                />
            </div>
        </section>
    @endif
@endsection