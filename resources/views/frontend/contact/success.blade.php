{{-- resources/views/frontend/contact/success.blade.php --}}
@php
    $pageTitle = 'Thank You - AgencyPro';
    $pageDescription = 'Thank you for contacting AgencyPro. We\'ll get back to you soon.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Success Message --}}
    <section class="py-32 bg-gradient-to-br from-[#f8f7ff] to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <div class="text-8xl mb-6">✅</div>
                <h1 class="text-4xl md:text-5xl font-bold text-[#0a0a0a] mb-4">Thank You!</h1>
                <p class="text-xl text-[#4a4a4a] mb-6">
                    We've received your message and we'll get back to you within 24 hours.
                </p>
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 text-left mb-8">
                    <h3 class="font-bold text-[#0a0a0a] mb-2">What Happens Next?</h3>
                    <ul class="space-y-3 text-[#4a4a4a]">
                        <li class="flex items-start gap-3">
                            <span class="text-[#6c5ce7] font-bold">1.</span>
                            <span>We'll review your inquiry within 2-4 hours</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-[#6c5ce7] font-bold">2.</span>
                            <span>We'll schedule a call to discuss your project</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-[#6c5ce7] font-bold">3.</span>
                            <span>We'll provide a detailed proposal and quote</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                        Return to Home
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors">
                        View Our Work
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Content --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <a href="{{ route('services.index') }}" class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group no-underline">
                    <div class="text-4xl mb-3">🚀</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Our Services</h4>
                    <p class="text-sm text-[#4a4a4a]">See how we can help your business grow</p>
                </a>
                <a href="{{ route('portfolio.index') }}" class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group no-underline">
                    <div class="text-4xl mb-3">💼</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Our Portfolio</h4>
                    <p class="text-sm text-[#4a4a4a]">See examples of our recent work</p>
                </a>
                <a href="{{ route('blog.index') }}" class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group no-underline">
                    <div class="text-4xl mb-3">📝</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Our Blog</h4>
                    <p class="text-sm text-[#4a4a4a]">Read our latest insights and tips</p>
                </a>
            </div>
        </div>
    </section>
@endsection