@extends('layouts.website')

@section('title', 'Template Details - ' . config('app.name'))
@section('description', 'View template details')

@section('content')
@php
    // If $template is not set, define it from the URL
    if (!isset($template)) {
        $slug = request()->route('slug');
        $allTemplates = [
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
                'reviews' => 156,
                'used_by' => 1234,
                'colors' => ['#4F46E5', '#7C3AED', '#EC4899']
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
                'reviews' => 89,
                'used_by' => 567,
                'colors' => ['#DC2626', '#F59E0B', '#92400E']
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
                'reviews' => 203,
                'used_by' => 890,
                'colors' => ['#EC4899', '#8B5CF6', '#F59E0B']
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
                'reviews' => 67,
                'used_by' => 345,
                'colors' => ['#3B82F6', '#06B6D4', '#10B981']
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
                'reviews' => 112,
                'used_by' => 678,
                'colors' => ['#0891B2', '#0EA5E9', '#10B981']
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
                'reviews' => 234,
                'used_by' => 1234,
                'colors' => ['#7C3AED', '#4F46E5', '#EC4899']
            ],
        ];
        
        $template = null;
        foreach ($allTemplates as $t) {
            if (\Illuminate\Support\Str::slug($t['name']) == $slug || $t['id'] == $slug) {
                $template = $t;
                break;
            }
        }
        
        if (!$template) {
            abort(404);
        }
        
        $templates = $allTemplates;
    }
@endphp
@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Breadcrumb --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-3">
            <ol class="flex items-center gap-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">Home</a>
                </li>
                <li>
                    <span class="text-gray-300">/</span>
                </li>
                <li>
                    <a href="{{ route('templates.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">Templates</a>
                </li>
                <li>
                    <span class="text-gray-300">/</span>
                </li>
                <li class="text-gray-900 font-medium">{{ $template['name'] }}</li>
            </ol>
        </div>
    </nav>

    {{-- Template Details --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                {{-- Template Image --}}
                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                        <img src="{{ $template['image'] }}" 
                             alt="{{ $template['name'] }}" 
                             class="w-full h-auto object-cover">
                        
                        {{-- Badges --}}
                        <div class="absolute top-4 left-4 flex gap-2">
                            @if($template['popular'] ?? false)
                                <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Popular
                                </span>
                            @endif
                            @if(($template['is_free'] ?? false) || $template['price'] === 'Free')
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">Free</span>
                            @endif
                            @if($template['is_new'] ?? false)
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow-lg">New</span>
                            @endif
                        </div>
                    </div>

                    {{-- Device Preview Buttons --}}
                    <div class="flex items-center justify-center gap-4 mt-4">
                        <button class="px-4 py-2 bg-gray-200 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Desktop
                        </button>
                        <button class="px-4 py-2 bg-gray-200 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Tablet
                        </button>
                        <button class="px-4 py-2 bg-gray-200 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Mobile
                        </button>
                    </div>
                </div>

                {{-- Template Info --}}
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ $template['category'] }}</span>
                        <span class="text-sm text-gray-400">|</span>
                        <span class="text-sm text-gray-500">{{ $template['pages'] ?? 6 }} pages</span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $template['name'] }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= ($template['rating'] ?? 4.5) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">{{ $template['reviews'] ?? 0 }} reviews</span>
                        <span class="text-sm text-gray-400">|</span>
                        <span class="text-sm text-gray-500">{{ $template['used_by'] ?? 0 }} users</span>
                    </div>

                    <p class="text-lg text-gray-600 leading-relaxed mb-8">{{ $template['description'] }}</p>

                    {{-- Price --}}
                    <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                        <div class="flex items-end gap-4">
                            <div>
                                <span class="text-4xl font-bold text-gray-900">{{ $template['price'] }}</span>
                                @if(($template['is_free'] ?? false) || $template['price'] === 'Free')
                                    <span class="text-sm text-gray-500 ml-2">Forever free</span>
                                @else
                                    <span class="text-sm text-gray-500 ml-2">One-time payment</span>
                                @endif
                            </div>
                            @if(!($template['is_free'] ?? false) && $template['price'] !== 'Free' && is_numeric(str_replace('$', '', $template['price'])))
                                @php
                                    $price = (float) str_replace('$', '', $template['price']);
                                    $originalPrice = $price * 2;
                                @endphp
                                <div class="ml-auto">
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($originalPrice, 0) }}</span>
                                    <span class="text-sm text-green-600 font-medium ml-2">50% off</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="#" onclick="useTemplate({{ $template['id'] }}); return false;" 
                           class="flex-1 px-8 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Use This Template
                        </a>
                        <a href="#" class="flex-1 px-8 py-4 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Live Preview
                        </a>
                    </div>

                    {{-- Features --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Features Included</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($template['features'] ?? ['Responsive Design', 'SEO Optimized', 'Fast Loading'] as $feature)
                                <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-lg">
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Color Palette --}}
                    @if(isset($template['colors']) && count($template['colors']) > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Color Palette</h3>
                            <div class="flex gap-3">
                                @foreach($template['colors'] as $color)
                                    <div class="w-12 h-12 rounded-full border-2 border-gray-200 shadow-sm" style="background: {{ $color }}"></div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Share --}}
                    <div class="pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-3">Share this template</p>
                        <div class="flex gap-3">
                            <button class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </button>
                            <button class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 01-1.93.07 4.28 4.28 0 004 2.98 8.521 8.521 0 01-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </button>
                            <button class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Templates --}}
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">You Might Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $related = array_slice($templates ?? [], 0, 4);
                @endphp
                @foreach($related as $relatedTemplate)
                    <a href="{{ route('templates.show', $relatedTemplate['id']) }}" 
                       class="group bg-gray-50 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <img src="{{ $relatedTemplate['image'] }}" 
                             alt="{{ $relatedTemplate['name'] }}" 
                             class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $relatedTemplate['name'] }}</h4>
                            <p class="text-sm text-gray-500">{{ $relatedTemplate['category'] }}</p>
                            <p class="text-sm font-bold text-indigo-600 mt-1">{{ $relatedTemplate['price'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script>
    function useTemplate(id) {
        @auth
            window.location.href = '/templates/' + id + '/use';
        @else
            window.location.href = "{{ route('register') }}?template=" + id;
        @endauth
    }
</script>
@endsection