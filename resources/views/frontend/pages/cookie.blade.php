{{-- resources/views/frontend/pages/cookie.blade.php --}}
@php
    $pageTitle = 'Cookie Policy - AgencyPro';
    $pageDescription = 'Learn how we use cookies to improve your experience on our website and how you can manage your cookie preferences.';
    $lastUpdated = 'December 20, 2024';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Cookie Policy"
        subtitle="How We Use Cookies"
        :breadcrumb="[['label' => 'Cookie Policy']]"
    >
        <x-slot:actions>
            <span class="text-sm text-[#4a4a4a]">Last Updated: {{ $lastUpdated }}</span>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Cookie Policy Content --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto prose prose-lg">
                <div class="bg-gradient-to-br from-[#f8f7ff] to-white rounded-2xl p-8 mb-8 border border-[#6c5ce7]/10">
                    <p class="text-[#0a0a0a] font-medium">We use cookies to enhance your browsing experience and provide personalized content. Learn more about our cookie practices.</p>
                </div>

                <h2>What Are Cookies?</h2>
                <p>Cookies are small text files placed on your device by websites you visit. They help us remember your preferences and improve your experience.</p>

                <h2>How We Use Cookies</h2>
                <ul>
                    <li><strong>Essential Cookies:</strong> Necessary for basic website functionality</li>
                    <li><strong>Performance Cookies:</strong> Help us understand how you use our website</li>
                    <li><strong>Functional Cookies:</strong> Remember your preferences and settings</li>
                    <li><strong>Analytics Cookies:</strong> Track and analyze website traffic</li>
                    <li><strong>Marketing Cookies:</strong> Personalize advertising content</li>
                </ul>

                <h2>Types of Cookies We Use</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-lg">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left">Cookie Type</th>
                                <th class="px-4 py-2 text-left">Purpose</th>
                                <th class="px-4 py-2 text-left">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border-t">session_token</td>
                                <td class="px-4 py-2 border-t">Maintain user session</td>
                                <td class="px-4 py-2 border-t">Session</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border-t">user_preferences</td>
                                <td class="px-4 py-2 border-t">Remember user settings</td>
                                <td class="px-4 py-2 border-t">1 year</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border-t">analytics_id</td>
                                <td class="px-4 py-2 border-t">Track website analytics</td>
                                <td class="px-4 py-2 border-t">2 years</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Managing Your Cookie Preferences</h2>
                <p>You can manage your cookie preferences at any time:</p>
                <ul>
                    <li><strong>Browser Settings:</strong> Control cookies through your browser settings</li>
                    <li><strong>Cookie Banner:</strong> Use the cookie consent banner on our website</li>
                    <li><strong>Opt-Out:</strong> Opt-out of analytics and marketing cookies</li>
                </ul>

                <h2>Third-Party Cookies</h2>
                <p>We also use third-party cookies from:</p>
                <ul>
                    <li>Google Analytics for website analytics</li>
                    <li>Social media platforms for sharing features</li>
                    <li>Payment processors for secure transactions</li>
                </ul>

                <h2>Questions About Cookies</h2>
                <p>If you have questions about our cookie policy, please contact us at:</p>
                <ul>
                    <li>Email: privacy@agencypro.com</li>
                    <li>Phone: +1 (555) 123-4567</li>
                </ul>
            </div>
        </div>
    </section>
@endsection