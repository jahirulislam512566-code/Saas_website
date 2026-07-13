@extends('website.layouts.app')

@section('title', 'Services')
@section('subtitle', 'Business')

@section('content')
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center text-gray-900">Our Services</h1>
            <div class="mt-12 grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl mb-4">🖥️</div>
                    <h3 class="font-semibold">Web Design</h3>
                    <p class="text-gray-600">Custom website designs tailored to your brand.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl mb-4">📈</div>
                    <h3 class="font-semibold">SEO Optimization</h3>
                    <p class="text-gray-600">Improve your visibility on search engines.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl mb-4">📱</div>
                    <h3 class="font-semibold">Mobile Apps</h3>
                    <p class="text-gray-600">Engage your audience with native mobile experiences.</p>
                </div>
            </div>
        </div>
    </section>
@endsection