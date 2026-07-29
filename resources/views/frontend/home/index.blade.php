{{-- resources/views/frontend/home/index.blade.php --}}
@extends('layouts.app')

@section('title', 'AgencyPro - Digital Agency')
@section('description', 'We build digital experiences that drive growth for forward-thinking brands.')

@section('schema')
    <x-seo.schema type="Organization" :data="[
        'name' => 'AgencyPro',
        'url' => url('/'),
        'description' => 'We build digital experiences that drive growth for forward-thinking brands.',
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
            'https://dribbble.com/agencypro',
        ],
    ]" />
@endsection

@section('content')
    {{-- 1. Hero --}}
    @include('frontend.home.hero')

    {{-- 2. Trusted Companies --}}
    @include('frontend.home.partners')

    {{-- 3. Services --}}
    @include('frontend.home.services')

    {{-- 4. Why Choose Us --}}
    @include('frontend.home.why-choose-us')

    {{-- 5. Features --}}
    @include('frontend.home.features')

    {{-- 6. Website Packages --}}
    @include('frontend.home.pricing')
    {{-- @include('frontend.home.packages') --}}

    {{-- 7. Process --}}
    @include('frontend.home.process')

    {{-- 8. Statistics --}}
    @include('frontend.home.stats')

    {{-- 9. Portfolio --}}
    @include('frontend.home.portfolio')

    {{-- 10. Technologies --}}
    @include('frontend.home.technologies')

    {{-- 11. Testimonials --}}
    {{-- @include('frontend.home.testimonials') --}}

    {{-- Testimonials Section --}}
    @if(isset($testimonials) && $testimonials->count() > 0)
        <x-sections.testimonials 
            :testimonials="$testimonials"
            title="What Our Clients Say"
            subtitle="Testimonials from our satisfied clients"
        />
    @endif

    {{-- 12. FAQ --}}
    @include('frontend.home.faq')

    {{-- 13. Latest Blog --}}
    @include('frontend.home.blog')

    {{-- 14. Newsletter --}}
    @include('frontend.home.newsletter')

    {{-- 15. Call To Action --}}
    @include('frontend.home.cta')
@endsection