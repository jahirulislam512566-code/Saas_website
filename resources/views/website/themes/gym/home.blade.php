@extends('layouts.website')

@section('title', 'Gym & Fitness - ' . config('app.name'))

@push('styles')
<style>
    .hero-gym {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/assets/images/gym-hero.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-gym min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
        <div class="max-w-3xl">
            <div class="inline-block bg-primary-500/20 backdrop-blur-sm text-primary-300 px-4 py-1 rounded-full text-sm font-semibold mb-6">
                💪 Join the Community
            </div>
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Transform Your <span class="text-primary-400">Body</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-300 font-light">Join the best gym in town. State-of-the-art equipment, expert trainers, and a supportive community.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <x-button variant="primary" size="lg" href="{{ route('themes.gym.contact') }}" icon="user-plus">Join Now</x-button>
                <x-button variant="outline" size="lg" href="{{ route('themes.gym.classes') }}" icon="calendar">View Classes</x-button>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach($stats ?? $defaultStats as $stat)
                <div class="text-center">
                    <div class="stat-number">{{ $stat['number'] ?? '0' }}</div>
                    <p class="text-gray-400 text-sm mt-2">{{ $stat['label'] ?? 'Stat' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Today's Classes -->
<section id="classes" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Schedule</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Today's Classes</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($todayClasses ?? $defaultTodayClasses as $class)
                <div class="class-card bg-white p-6 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border-l-4 border-primary-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">{{ $class['name'] ?? 'Class' }}</h3>
                            <p class="text-sm text-gray-500">{{ $class['instructor'] ?? 'Instructor' }}</p>
                        </div>
                        <x-badge variant="primary" size="lg">{{ $class['time'] ?? 'Time' }}</x-badge>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm">
                        <span class="text-gray-600">
                            <x-icon name="user" class="w-4 h-4 inline mr-1" />
                            {{ $class['spots'] ?? 0 }} spots available
                        </span>
                        <x-button variant="primary" size="sm">Book Now</x-button>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-10">
            <x-button variant="primary" href="{{ route('themes.gym.classes') }}" icon="calendar">View Full Schedule</x-button>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($features ?? $defaultFeatures as $feature)
                <div class="text-center p-6">
                    <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">{{ $feature['icon'] ?? '💪' }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] ?? 'Feature' }}</h3>
                    <p class="text-gray-600">{{ $feature['description'] ?? 'Description' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@php
$defaultStats = [
    ['number' => '500+', 'label' => 'Members'],
    ['number' => '50+', 'label' => 'Classes Weekly'],
    ['number' => '20+', 'label' => 'Expert Trainers'],
    ['number' => '4.9★', 'label' => 'Rating']
];

$defaultTodayClasses = [
    ['name' => 'Yoga Flow', 'instructor' => 'Sarah Johnson', 'time' => '9:00 AM', 'spots' => 8],
    ['name' => 'HIIT Training', 'instructor' => 'Mike Thompson', 'time' => '10:30 AM', 'spots' => 12],
    ['name' => 'Spinning', 'instructor' => 'Lisa Anderson', 'time' => '12:00 PM', 'spots' => 5]
];

$defaultFeatures = [
    ['icon' => '💪', 'title' => 'Modern Equipment', 'description' => 'State-of-the-art fitness equipment from top brands'],
    ['icon' => '🧘', 'title' => 'Expert Trainers', 'description' => 'Certified trainers with years of experience'],
    ['icon' => '🏋️', 'title' => 'Group Classes', 'description' => 'Diverse range of group fitness classes']
];
@endphp