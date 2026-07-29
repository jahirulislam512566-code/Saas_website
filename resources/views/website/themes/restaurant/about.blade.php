@extends('layouts.website')

@section('title', 'About Our Restaurant - ' . config('app.name'))

@section('content')
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Our Story</span>
                <h1 class="text-4xl md:text-5xl font-bold mt-2 mb-6 text-gray-900">Where Passion Meets<br><span class="text-primary-600">Culinary Excellence</span></h1>
                <div class="prose prose-lg text-gray-600">
                    <p>Founded in 2010, our restaurant has been dedicated to bringing authentic flavors from around the world to your table. Our passion for food and hospitality drives everything we do.</p>
                    <p class="mt-4">We believe in using only the freshest ingredients, sourced locally whenever possible, to create dishes that delight and inspire.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="text-3xl font-bold text-primary-600">10+</div>
                        <div class="text-sm text-gray-600">Years of Excellence</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="text-3xl font-bold text-primary-600">500+</div>
                        <div class="text-sm text-gray-600">Happy Customers</div>
                    </div>
                </div>
                
                <div class="flex space-x-4 mt-8">
                    <x-button variant="primary" href="{{ route('themes.restaurant.menu') }}" icon="menu">Explore Menu</x-button>
                    <x-button variant="outline" href="#chef" icon="user">Meet Our Chef</x-button>
                </div>
            </div>
            
            <div class="relative">
                <img src="/assets/images/about-restaurant.jpg" alt="Our Restaurant" class="rounded-2xl shadow-2xl w-full">
                <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-xl shadow-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-3xl">🏆</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Award Winning</p>
                            <p class="text-sm text-gray-600">Best Restaurant 2023</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chef's Philosophy -->
<section id="chef" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Philosophy</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Chef's Culinary Vision</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($philosophy ?? $defaultPhilosophy as $item)
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-lg transition text-center group">
                    <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition">
                        <x-icon name="{{ $item['icon'] ?? 'star' }}" class="w-10 h-10 text-primary-600 group-hover:text-white transition" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item['title'] ?? 'Philosophy' }}</h3>
                    <p class="text-gray-600">{{ $item['description'] ?? 'Description' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@php
$defaultPhilosophy = [
    ['icon' => 'heart', 'title' => 'Passion for Food', 'description' => 'Every dish is created with love and attention to detail'],
    ['icon' => 'leaf', 'title' => 'Fresh Ingredients', 'description' => 'We source only the freshest, highest quality ingredients'],
    ['icon' => 'star', 'title' => 'Perfection', 'description' => 'Striving for excellence in every plate we serve']
];
@endphp