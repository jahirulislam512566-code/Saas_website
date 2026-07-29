{{-- resources/views/frontend/services/hosting.blade.php --}}
@php
    $service = [
        'title' => 'Hosting Solutions',
        'description' => 'We provide fast, secure, and reliable hosting solutions optimized for performance, scalability, and uptime.',
        'icon' => '☁️',
        'features' => [
            'Cloud hosting (AWS, Google Cloud, Azure)',
            'Managed WordPress hosting',
            'Custom server configuration',
            'SSL certificates',
            'CDN integration',
            'Daily backups',
            '24/7 monitoring',
            'Scalable infrastructure',
        ],
        'process' => [
            ['number' => '01', 'title' => 'Assessment', 'description' => 'We evaluate your hosting needs.'],
            ['number' => '02', 'title' => 'Setup', 'description' => 'We configure your infrastructure.'],
            ['number' => '03', 'title' => 'Migrate', 'description' => 'We move your site to our servers.'],
            ['number' => '04', 'title' => 'Monitor', 'description' => 'We provide ongoing support.'],
        ],
        'faqs' => [
            ['question' => 'What hosting options do you offer?', 'answer' => 'We offer cloud hosting with AWS, Google Cloud, and Azure, as well as managed WordPress hosting and custom server solutions.'],
            ['question' => 'Do you provide migration services?', 'answer' => 'Yes, we handle the entire migration process with zero downtime.'],
        ],
        'relatedServices' => [
            ['title' => 'Web Development', 'description' => 'Custom web applications', 'icon' => '💻', 'url' => route('services.web-development')],
            ['title' => 'Maintenance', 'description' => 'Ongoing support', 'icon' => '🔧', 'url' => route('services.maintenance')],
            ['title' => 'SEO & Marketing', 'description' => 'Growth strategies', 'icon' => '🔍', 'url' => route('services.seo')],
        ],
    ];
@endphp

@extends('frontend.services.show', ['service' => $service])