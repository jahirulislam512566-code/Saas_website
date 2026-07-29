{{-- resources/views/frontend/services/maintenance.blade.php --}}
@php
    $service = [
        'title' => 'Maintenance & Support',
        'description' => 'We provide reliable maintenance and support services to keep your digital assets secure, updated, and performing optimally.',
        'icon' => '🔧',
        'features' => [
            '24/7 monitoring and support',
            'Security patches and updates',
            'Performance optimization',
            'Bug fixing and troubleshooting',
            'Content updates',
            'Backup and recovery',
            'Technical consultation',
            'Emergency response',
        ],
        'process' => [
            ['number' => '01', 'title' => 'Assessment', 'description' => 'We evaluate your current setup.'],
            ['number' => '02', 'title' => 'Plan', 'description' => 'We create a maintenance schedule.'],
            ['number' => '03', 'title' => 'Execute', 'description' => 'We perform regular maintenance.'],
            ['number' => '04', 'title' => 'Review', 'description' => 'We report and optimize.'],
        ],
        'faqs' => [
            ['question' => 'What does maintenance include?', 'answer' => 'Our maintenance packages include security updates, performance monitoring, bug fixes, content updates, and priority support.'],
            ['question' => 'How quickly do you respond to issues?', 'answer' => 'We provide different response times based on your plan. Our premium plans include 24/7 emergency support.'],
        ],
        'relatedServices' => [
            ['title' => 'Web Development', 'description' => 'Custom web applications', 'icon' => '💻', 'url' => route('services.web-development')],
            ['title' => 'Hosting', 'description' => 'Reliable hosting', 'icon' => '☁️', 'url' => route('services.hosting')],
            ['title' => 'SEO & Marketing', 'description' => 'Growth strategies', 'icon' => '🔍', 'url' => route('services.seo')],
        ],
    ];
@endphp

@extends('frontend.services.show', ['service' => $service])