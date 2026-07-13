@extends('website.layouts.app')

@section('title', 'About Us')
@section('subtitle', 'Business')

@section('content')
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900">About Our Company</h1>
            <p class="mt-6 text-xl text-gray-600">We are a team of passionate entrepreneurs dedicated to helping businesses thrive online.</p>
            <div class="mt-12 grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-semibold text-lg">Our Mission</h3>
                    <p class="text-gray-600">Empower small businesses with easy‑to‑use digital tools.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Our Vision</h3>
                    <p class="text-gray-600">A world where every business has a stunning online presence.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Our Values</h3>
                    <p class="text-gray-600">Integrity, innovation, and customer success.</p>
                </div>
            </div>
        </div>
    </section>
@endsection