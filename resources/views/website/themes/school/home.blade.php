@extends('layouts.website')

@section('title', 'School of Excellence - ' . config('app.name'))

@push('styles')
<style>
    .hero-school {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/assets/images/school-hero.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .feature-icon {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-school min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
        <div class="max-w-3xl">
            <div class="inline-block bg-indigo-500/20 backdrop-blur-sm text-indigo-300 px-4 py-1 rounded-full text-sm font-semibold mb-6">
                🎓 Excellence in Education
            </div>
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Nurturing <span class="text-indigo-400">Future Leaders</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-300 font-light">Providing quality education that empowers students to reach their full potential.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <x-button variant="primary" size="lg" href="{{ route('themes.school.contact') }}" icon="user-plus">Apply Now</x-button>
                <x-button variant="outline" size="lg" href="{{ route('themes.school.about') }}" icon="info">Learn More</x-button>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($features ?? $defaultFeatures as $feature)
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition text-center">
                    <div class="w-16 h-16 feature-icon rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">{{ $feature['icon'] ?? '📚' }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] ?? 'Feature' }}</h3>
                    <p class="text-gray-600 text-sm">{{ $feature['description'] ?? 'Description' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Upcoming Events -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Calendar</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Upcoming Events</h2>
            <div class="w-24 h-1 bg-indigo-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($events ?? $defaultEvents as $event)
                <div class="event-card bg-white p-6 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-2xl font-bold text-indigo-600">{{ $event['day'] ?? '00' }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $event['title'] ?? 'Event' }}</h3>
                            <p class="text-sm text-gray-500">{{ $event['date'] ?? 'Date' }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">{{ $event['description'] ?? 'Description' }}</p>
                    <div class="mt-4">
                        <x-badge variant="primary" size="sm">View Details</x-badge>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-indigo-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-300 font-semibold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2">What Parents Say</h2>
            <div class="w-24 h-1 bg-indigo-400 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($parentTestimonials ?? $defaultParentTestimonials as $testimonial)
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                    <p class="text-gray-300 italic">"{{ $testimonial['text'] ?? 'Testimonial' }}"</p>
                    <div class="flex items-center mt-4">
                        <x-avatar name="{{ $testimonial['name'] ?? 'Parent' }}" size="sm" />
                        <div class="ml-3">
                            <p class="font-semibold">{{ $testimonial['name'] ?? 'Name' }}</p>
                            <p class="text-sm text-indigo-300">{{ $testimonial['position'] ?? 'Position' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@php
$defaultFeatures = [
    ['icon' => '📚', 'title' => 'Quality Education', 'description' => 'Comprehensive curriculum designed for excellence'],
    ['icon' => '👨‍🏫', 'title' => 'Expert Teachers', 'description' => 'Qualified and experienced teaching staff'],
    ['icon' => '🏫', 'title' => 'Modern Facilities', 'description' => 'State-of-the-art classrooms and laboratories']
];

$defaultEvents = [
    ['title' => 'Open House', 'day' => '15', 'date' => 'March 15, 2024', 'description' => 'Visit our campus and meet our teachers'],
    ['title' => 'Science Fair', 'day' => '22', 'date' => 'March 22, 2024', 'description' => 'Student science projects and experiments'],
    ['title' => 'Sports Day', 'day' => '29', 'date' => 'March 29, 2024', 'description' => 'Annual sports competition and activities']
];

$defaultParentTestimonials = [
    ['name' => 'John Smith', 'text' => 'My child has flourished academically and socially at this school.', 'position' => 'Parent of Grade 5 Student'],
    ['name' => 'Mary Johnson', 'text' => 'The teachers are dedicated and truly care about each student\'s success.', 'position' => 'Parent of Grade 8 Student'],
    ['name' => 'David Williams', 'text' => 'Excellent school with a strong focus on holistic development.', 'position' => 'Parent of Grade 3 Student']
];
@endphp