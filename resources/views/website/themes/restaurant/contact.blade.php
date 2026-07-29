@extends('layouts.website')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Get in Touch</span>
                <h1 class="text-4xl md:text-5xl font-bold mt-2 mb-6 text-gray-900">We'd Love to<br><span class="text-primary-600">Hear From You</span></h1>
                <p class="text-lg text-gray-600 mb-8">Whether you have a question about our menu, want to book a private event, or just want to share your experience, we're here to help.</p>
                
                <div class="space-y-4">
                    @foreach($contactInfo ?? $defaultContactInfo as $info)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="{{ $info['icon'] ?? 'user' }}" class="w-6 h-6 text-primary-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ $info['label'] ?? 'Label' }}</p>
                                <p class="font-medium text-gray-900">{{ $info['value'] ?? 'Value' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 bg-primary-50 p-6 rounded-xl">
                    <h3 class="font-semibold text-gray-900 mb-4">Opening Hours</h3>
                    <div class="space-y-2">
                        @foreach($hours ?? $defaultHours as $day => $time)
                            <div class="flex justify-between py-2 border-b border-primary-100">
                                <span class="text-gray-600">{{ $day }}</span>
                                <span class="font-medium text-gray-900">{{ $time }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div>
                <x-card title="Send us a Message" subtitle="We'll get back to you within 24 hours" class="shadow-xl">
                    <form action="{{ route('themes.restaurant.contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <x-input name="name" label="Your Name" placeholder="John Doe" required />
                        <x-input name="email" label="Email Address" type="email" placeholder="john@example.com" required />
                        <x-input name="phone" label="Phone Number" type="tel" placeholder="+1 (555) 123-4567" />
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea name="message" id="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition" placeholder="Your message..." required></textarea>
                        </div>
                        <x-button variant="primary" type="submit" class="w-full" icon="send">Send Message</x-button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</section>

<!-- Location Map -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Location</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Find Us</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
        </div>
        <div class="h-96 bg-gray-200 rounded-2xl overflow-hidden shadow-lg">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1423697624433!2d-73.987318684594!3d40.74844097932795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1646345487675!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>
@endsection

@php
$defaultContactInfo = [
    ['icon' => 'user', 'label' => 'Address', 'value' => '123 Main St, New York, NY 10001'],
    ['icon' => 'cog', 'label' => 'Phone', 'value' => '+1 (555) 123-4567'],
    ['icon' => 'star', 'label' => 'Email', 'value' => 'info@restaurant.com']
];

$defaultHours = [
    'Monday - Friday' => '11:00 AM - 10:00 PM',
    'Saturday' => '10:00 AM - 11:00 PM',
    'Sunday' => '12:00 PM - 9:00 PM'
];
@endphp