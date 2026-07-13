@extends('layouts.landing')

@section('content')
    <section class="py-20 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center text-gray-900">Contact Us</h1>
            <form action="#" method="POST" class="mt-8 bg-white p-8 rounded-2xl shadow-sm">
                @csrf
                <div class="space-y-4">
                    <input type="text" placeholder="Your Name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="email" placeholder="Your Email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <textarea rows="5" placeholder="Message" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">Send Message</button>
                </div>
            </form>
        </div>
    </section>
@endsection