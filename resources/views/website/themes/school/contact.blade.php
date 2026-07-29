@extends('layouts.website')

@section('title', 'Contact Us')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h1 class="text-4xl font-bold mb-6">Get in Touch</h1>
                <p class="text-lg text-gray-600 mb-8">Have questions about admissions or programs? We're here to help.</p>
                
                <div class="space-y-4">
                    @foreach($contactInfo ?? [
                        ['icon' => 'user', 'label' => 'Phone', 'value' => '+1 (555) 123-4567'],
                        ['icon' => 'cog', 'label' => 'Email', 'value' => 'info@school.com'],
                        ['icon' => 'star', 'label' => 'Address', 'value' => '123 Education St, City, State']
                    ] as $info)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                <x-icon name="{{ $info['icon'] }}" class="w-6 h-6 text-primary-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ $info['label'] }}</p>
                                <p class="font-medium">{{ $info['value'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div>
                <x-card title="Admission Inquiry">
                    <form action="/contact" method="POST" class="space-y-4">
                        @csrf
                        <x-input name="name" label="Full Name" required />
                        <x-input name="email" label="Email Address" type="email" required />
                        <x-input name="phone" label="Phone Number" type="tel" required />
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea name="message" id="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition" required></textarea>
                        </div>
                        <x-button variant="primary" type="submit" class="w-full">Submit Inquiry</x-button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</section>
@endsection