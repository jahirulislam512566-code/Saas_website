@extends('layouts.website')

@section('title', 'Professional Website Templates - ' . config('app.name'))
@section('description', 'Choose from 100+ professional website templates for every industry. Business, Portfolio, Restaurant, Health, Education, E-commerce and more.')

@section('content')
@php
    $templates = [
        [
            'id' => 1,
            'name' => 'Business Pro',
            'category' => 'Business',
            'category_slug' => 'business',
            'description' => 'Modern business template with professional design and advanced features.',
            'image' => 'https://placehold.co/600x400/4F46E5/white?text=Business+Pro',
            'pages' => 12,
            'price' => 'Free',
            'popular' => true,
            'is_free' => true,
            'is_new' => false,
            'features' => ['Responsive', 'SEO Optimized', 'Blog Ready'],
            'rating' => 4.8,
            'reviews' => 156
        ],
        [
            'id' => 2,
            'name' => 'Restaurant Deluxe',
            'category' => 'Restaurant',
            'category_slug' => 'restaurant',
            'description' => 'Beautiful restaurant template with menu showcase and reservation system.',
            'image' => 'https://placehold.co/600x400/DC2626/white?text=Restaurant+Deluxe',
            'pages' => 8,
            'price' => '$49',
            'popular' => true,
            'is_free' => false,
            'is_new' => false,
            'features' => ['Menu Builder', 'Reservation', 'Gallery'],
            'rating' => 4.7,
            'reviews' => 89
        ],
        [
            'id' => 3,
            'name' => 'Portfolio Creative',
            'category' => 'Portfolio',
            'category_slug' => 'portfolio',
            'description' => 'Stunning portfolio template for creative professionals and artists.',
            'image' => 'https://placehold.co/600x400/EC4899/white?text=Portfolio+Creative',
            'pages' => 6,
            'price' => 'Free',
            'popular' => false,
            'is_free' => true,
            'is_new' => true,
            'features' => ['Gallery', 'Filter', 'Lightbox'],
            'rating' => 4.9,
            'reviews' => 203
        ],
        [
            'id' => 4,
            'name' => 'School Academy',
            'category' => 'School',
            'category_slug' => 'school',
            'description' => 'Complete school website template with course management and events.',
            'image' => 'https://placehold.co/600x400/3B82F6/white?text=School+Academy',
            'pages' => 10,
            'price' => '$79',
            'popular' => false,
            'is_free' => false,
            'is_new' => false,
            'features' => ['Courses', 'Events', 'Staff Directory'],
            'rating' => 4.6,
            'reviews' => 67
        ],
        [
            'id' => 5,
            'name' => 'Doctor Health',
            'category' => 'Doctor',
            'category_slug' => 'doctor',
            'description' => 'Professional medical template with appointment booking and patient management.',
            'image' => 'https://placehold.co/600x400/0891B2/white?text=Doctor+Health',
            'pages' => 9,
            'price' => '$59',
            'popular' => true,
            'is_free' => false,
            'is_new' => false,
            'features' => ['Appointments', 'Services', 'Patient Portal'],
            'rating' => 4.8,
            'reviews' => 112
        ],
        [
            'id' => 6,
            'name' => 'Agency Modern',
            'category' => 'Agency',
            'category_slug' => 'agency',
            'description' => 'Modern agency template with project showcase and team management.',
            'image' => 'https://placehold.co/600x400/7C3AED/white?text=Agency+Modern',
            'pages' => 14,
            'price' => 'Free',
            'popular' => true,
            'is_free' => true,
            'is_new' => false,
            'features' => ['Projects', 'Team', 'Client Portal'],
            'rating' => 4.9,
            'reviews' => 234
        ],
        [
            'id' => 7,
            'name' => 'Construction Pro',
            'category' => 'Construction',
            'category_slug' => 'construction',
            'description' => 'Professional construction template with project portfolio and service showcase.',
            'image' => 'https://placehold.co/600x400/F59E0B/white?text=Construction+Pro',
            'pages' => 10,
            'price' => '$49',
            'popular' => false,
            'is_free' => false,
            'is_new' => true,
            'features' => ['Projects', 'Services', 'Gallery'],
            'rating' => 4.5,
            'reviews' => 45
        ],
        [
            'id' => 8,
            'name' => 'Law Firm',
            'category' => 'Law Firm',
            'category_slug' => 'law-firm',
            'description' => 'Elegant law firm template with practice areas and attorney profiles.',
            'image' => 'https://placehold.co/600x400/1E293B/white?text=Law+Firm',
            'pages' => 8,
            'price' => '$69',
            'popular' => false,
            'is_free' => false,
            'is_new' => false,
            'features' => ['Practice Areas', 'Attorney Profiles', 'Case Studies'],
            'rating' => 4.7,
            'reviews' => 78
        ],
        [
            'id' => 9,
            'name' => 'Travel Explorer',
            'category' => 'Travel',
            'category_slug' => 'travel',
            'description' => 'Adventure travel template with destination showcase and booking system.',
            'image' => 'https://placehold.co/600x400/06B6D4/white?text=Travel+Explorer',
            'pages' => 12,
            'price' => '$59',
            'popular' => false,
            'is_free' => false,
            'is_new' => true,
            'features' => ['Destinations', 'Booking', 'Gallery'],
            'rating' => 4.6,
            'reviews' => 56
        ],
        [
            'id' => 10,
            'name' => 'Photography Studio',
            'category' => 'Photography',
            'category_slug' => 'photography',
            'description' => 'Stunning photography template with portfolio gallery and client booking.',
            'image' => 'https://placehold.co/600x400/8B5CF6/white?text=Photography+Studio',
            'pages' => 8,
            'price' => 'Free',
            'popular' => true,
            'is_free' => true,
            'is_new' => false,
            'features' => ['Gallery', 'Booking', 'Client Portal'],
            'rating' => 4.8,
            'reviews' => 167
        ],
        [
            'id' => 11,
            'name' => 'Gym Fitness',
            'category' => 'Gym',
            'category_slug' => 'gym',
            'description' => 'Modern gym template with class schedules, membership, and trainer profiles.',
            'image' => 'https://placehold.co/600x400/EF4444/white?text=Gym+Fitness',
            'pages' => 10,
            'price' => '$39',
            'popular' => false,
            'is_free' => false,
            'is_new' => false,
            'features' => ['Class Schedule', 'Membership', 'Trainers'],
            'rating' => 4.4,
            'reviews' => 34
        ],
    ];
@endphp

<div class="min-h-screen bg-gray-50">
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Choose Your Perfect Template
                </h1>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Browse our collection of professionally designed templates and launch your website in minutes
                </p>
                <div class="mt-6 flex items-center justify-center gap-4 text-sm text-white/80">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        100+ Templates
                    </span>
                    <span class="w-px h-4 bg-white/30"></span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Free & Premium
                    </span>
                    <span class="w-px h-4 bg-white/30"></span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Mobile Responsive
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters & Templates --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            {{-- Filters --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-8" x-data="templateFilters()">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    {{-- Search --}}
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                               x-model="searchTerm"
                               @input="filterTemplates()"
                               placeholder="Search templates..." 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                    </div>

                    {{-- Category Filter --}}
                    <div class="flex flex-wrap gap-2" x-ref="filterContainer">
                        <button @click="setActiveCategory('all')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            All Templates
                        </button>
                        <button @click="setActiveCategory('business')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'business' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Business
                        </button>
                        <button @click="setActiveCategory('agency')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'agency' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Agency
                        </button>
                        <button @click="setActiveCategory('restaurant')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'restaurant' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Restaurant
                        </button>
                        <button @click="setActiveCategory('gym')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'gym' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Gym
                        </button>
                        <button @click="setActiveCategory('school')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'school' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            School
                        </button>
                        <button @click="setActiveCategory('portfolio')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'portfolio' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Portfolio
                        </button>
                        <button @click="setActiveCategory('construction')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'construction' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Construction
                        </button>
                        <button @click="setActiveCategory('law-firm')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'law-firm' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Law Firm
                        </button>
                        <button @click="setActiveCategory('doctor')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'doctor' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Doctor
                        </button>
                        <button @click="setActiveCategory('travel')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'travel' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Travel
                        </button>
                        <button @click="setActiveCategory('photography')" 
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="activeCategory === 'photography' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Photography
                        </button>
                    </div>

                    {{-- View Toggle --}}
                    <div class="flex items-center gap-2 ml-auto">
                        <button @click="viewMode = 'grid'" 
                                class="p-2 rounded-lg transition-colors"
                                :class="viewMode === 'grid' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 hover:bg-gray-100'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button @click="viewMode = 'list'" 
                                class="p-2 rounded-lg transition-colors"
                                :class="viewMode === 'list' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 hover:bg-gray-100'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Active Filters Count --}}
                <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                    <span x-text="filteredCount + ' templates found'">0 templates found</span>
                    <span x-show="activeCategory !== 'all' || searchTerm" class="flex items-center gap-1">
                        <span class="text-gray-300">|</span>
                        <span>Filtered by:</span>
                        <span x-show="activeCategory !== 'all'" class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs" x-text="activeCategory"></span>
                        <span x-show="searchTerm" class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs" x-text="'Search: ' + searchTerm"></span>
                        <button @click="clearFilters()" class="text-indigo-600 hover:text-indigo-800 font-medium ml-1">Clear all</button>
                    </span>
                </div>
            </div>

            {{-- Templates Grid --}}
            <div x-data="templateFilters()" 
                 x-init="initTemplates({{ json_encode($templates) }})"
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <template x-for="template in filteredTemplates" :key="template.id">
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden hover:-translate-y-2">
                        {{-- Image --}}
                        <div class="relative overflow-hidden">
                            <img :src="template.image" 
                                 :alt="template.name" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-700">
                            
                            {{-- Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <div class="p-6 w-full">
                                    <div class="flex gap-2">
                                        <button @click="previewTemplate(template.id)" 
                                                class="flex-1 px-4 py-2.5 bg-white text-gray-900 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                            <span class="flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Preview
                                            </span>
                                        </button>
                                        <button @click="useTemplate(template.id)" 
                                                class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                                            <span class="flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Use Template
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span x-show="template.popular" 
                                      class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Popular
                                </span>
                                <span x-show="template.is_free" 
                                      class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    Free
                                </span>
                                <span x-show="template.is_new" 
                                      class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    New
                                </span>
                            </div>

                            {{-- Price --}}
                            <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-bold rounded-lg shadow-lg">
                                <span x-text="template.price"></span>
                            </div>

                            {{-- Category Tag --}}
                            <div class="absolute bottom-4 left-4 px-3 py-1 bg-black/60 backdrop-blur-sm text-white text-xs font-medium rounded-lg">
                                <span x-text="template.category"></span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors" x-text="template.name"></h3>
                                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded-full" x-text="template.pages + ' pages'"></span>
                            </div>
                            
                            <p class="text-gray-600 text-sm leading-relaxed mb-4" x-text="template.description"></p>

                            {{-- Features --}}
                            <div class="flex flex-wrap gap-2">
                                <template x-for="feature in template.features" :key="feature">
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-600 text-xs font-medium rounded-full" x-text="feature"></span>
                                </template>
                            </div>

                            {{-- Rating --}}
                            <div class="mt-4 flex items-center gap-3 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-1">
                                    <template x-for="i in 5" :key="i">
                                        <svg class="w-4 h-4" :class="i <= template.rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-sm text-gray-500" x-text="template.reviews + ' reviews'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- No Results --}}
            <div x-show="filteredTemplates.length === 0" class="text-center py-20">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No templates found</h3>
                <p class="text-gray-600">Try adjusting your search or filter criteria</p>
                <button @click="clearFilters()" class="mt-4 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors">
                    Clear Filters
                </button>
            </div>

            {{-- Load More --}}
            <div class="text-center mt-12" x-show="filteredTemplates.length > 0">
                <button class="px-8 py-4 bg-white text-gray-700 font-bold rounded-xl hover:shadow-xl transition-all duration-300 border-2 border-gray-200 hover:border-indigo-600 hover:text-indigo-600">
                    Load More Templates
                </button>
            </div>
        </div>
    </section>
</div>

{{-- Pass templates data to JavaScript for the modal --}}
<script>
    window.templatesData = @json($templates);
</script>
{{-- Preview Modal --}}
<x-template-preview-modal />

{{-- @push('scripts')
<script>
    function templateFilters() {
        return {
            activeCategory: 'all',
            searchTerm: '',
            viewMode: 'grid',
            templates: [],
            filteredTemplates: [],
            filteredCount: 0,

            initTemplates(templates) {
                this.templates = templates;
                this.filterTemplates();
            },

            filterTemplates() {
                this.filteredTemplates = this.templates.filter(template => {
                    // Category filter
                    if (this.activeCategory !== 'all' && template.category_slug !== this.activeCategory) {
                        return false;
                    }
                    
                    // Search filter
                    if (this.searchTerm) {
                        const search = this.searchTerm.toLowerCase();
                        const name = template.name.toLowerCase();
                        const description = template.description.toLowerCase();
                        const category = template.category.toLowerCase();
                        if (!name.includes(search) && !description.includes(search) && !category.includes(search)) {
                            return false;
                        }
                    }
                    
                    return true;
                });
                
                this.filteredCount = this.filteredTemplates.length;
            },

            setActiveCategory(category) {
                this.activeCategory = category;
                this.filterTemplates();
            },

            clearFilters() {
                this.activeCategory = 'all';
                this.searchTerm = '';
                this.filterTemplates();
            },

            previewTemplate(id) {
                window.dispatchEvent(new CustomEvent('preview-template', { detail: { id } }));
            },

            useTemplate(id) {
                @auth
                    window.location.href = "{{ route('templates.use') }}/" + id;
                @else
                    window.location.href = "{{ route('register') }}?template=" + id;
                @endauth
            }
        }
    }
</script>
@endpush --}}