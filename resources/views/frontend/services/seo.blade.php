{{-- resources/views/frontend/services/seo.blade.php --}}
@php
    $service = [
        'title' => 'SEO & Marketing',
        'description' => 'We drive organic growth through data-driven SEO strategies and comprehensive digital marketing campaigns.',
        'icon' => '🔍',
        'features' => [
            'Technical SEO audit',
            'Keyword research and strategy',
            'On-page optimization',
            'Content marketing',
            'Link building',
            'Local SEO',
            'Performance tracking',
            'Conversion rate optimization',
        ],
        'process' => [
            ['number' => '01', 'title' => 'Audit', 'description' => 'We analyze your current position.'],
            ['number' => '02', 'title' => 'Strategy', 'description' => 'We create a customized plan.'],
            ['number' => '03', 'title' => 'Execute', 'description' => 'We implement the strategy.'],
            ['number' => '04', 'title' => 'Monitor', 'description' => 'We track and optimize results.'],
        ],
        'faqs' => [
            ['question' => 'How long does SEO take?', 'answer' => 'SEO is a long-term strategy. Typically, you start seeing results in 3-6 months, with continuous growth over time.'],
            ['question' => 'Do you guarantee results?', 'answer' => 'We guarantee our work and processes, and we always focus on delivering measurable results.'],
        ],
        'relatedServices' => [
            ['title' => 'Web Development', 'description' => 'Custom web applications', 'icon' => '💻', 'url' => route('services.web-development')],
            ['title' => 'Web Design', 'description' => 'Beautiful designs', 'icon' => '🎨', 'url' => route('services.web-design')],
            ['title' => 'E-Commerce', 'description' => 'Online stores', 'icon' => '🛒', 'url' => route('services.ecommerce')],
        ],
    ];
@endphp

@extends('frontend.services.show', ['service' => $service])