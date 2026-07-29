@extends('layouts.website')

@section('title', 'About Our School')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h1 class="text-4xl font-bold mb-6">Our History</h1>
                <p class="text-lg text-gray-600 mb-6">Founded in 1990, our school has been committed to providing quality education for over three decades.</p>
                <p class="text-lg text-gray-600 mb-6">We believe in nurturing young minds and preparing them for a successful future.</p>
                
                <div class="flex space-x-4">
                    <x-button variant="primary" href="/courses">View Courses</x-button>
                    <x-button variant="outline" href="#principal">Principal's Message</x-button>
                </div>
            </div>
            <div>
                <img src="/assets/images/school-building.jpg" alt="School Building" class="rounded-lg shadow-lg w-full">
            </div>
        </div>
    </div>
</section>

<!-- Principal's Message -->
<section id="principal" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-8">Principal's Message</h2>
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <x-avatar name="Dr. Robert Smith" size="lg" class="mx-auto mb-4" />
                <p class="text-gray-600 italic mb-4">"Education is the most powerful weapon which you can use to change the world."</p>
                <p class="text-gray-600">Welcome to our school. We are committed to providing an exceptional learning environment where every student can thrive and reach their full potential.</p>
                <p class="font-semibold mt-4">Dr. Robert Smith</p>
                <p class="text-sm text-gray-500">Principal</p>
            </div>
        </div>
    </div>
</section>
@endsection