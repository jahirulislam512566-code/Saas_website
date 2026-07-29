{{-- resources/views/frontend/services/web-development.blade.php --}}
@php
    $service = [
        'title' => 'Web Development',
        'description' => 'We build custom web applications that are fast, secure, and scalable. Using modern technologies and best practices, we create solutions that drive business growth.',
        'icon' => '💻',
        'features' => [
            'Custom web application development',
            'API development and integration',
            'Database design and optimization',
            'Cloud infrastructure setup',
            'Performance optimization',
            'Security implementation',
            'Third-party integrations',
            'CMS development (WordPress, Laravel, etc.)',
        ],
        'process' => [
            ['number' => '01', 'title' => 'Discovery', 'description' => 'We understand your needs and goals.'],
            ['number' => '02', 'title' => 'Planning', 'description' => 'We create a detailed project roadmap.'],
            ['number' => '03', 'title' => 'Development', 'description' => 'We build and iterate on your solution.'],
            ['number' => '04', 'title' => 'Launch', 'description' => 'We deploy and ensure everything works.'],
        ],
        'faqs' => [
            ['question' => 'What technologies do you use?', 'answer' => 'We use a variety of modern technologies including React, Vue.js, Laravel, Node.js, Python, and more, depending on your specific needs.'],
            ['question' => 'How long does development take?', 'answer' => 'Timeline varies based on project complexity. A typical web application takes 8-16 weeks, but we provide detailed estimates during discovery.'],
            ['question' => 'Do you provide maintenance?', 'answer' => 'Yes, we offer ongoing maintenance and support packages to keep your application running smoothly.'],
        ],
        'relatedServices' => [
            ['title' => 'Web Design', 'description' => 'Beautiful, user-centered designs', 'icon' => '🎨', 'url' => route('services.web-design')],
            ['title' => 'E-Commerce', 'description' => 'Powerful online stores', 'icon' => '🛒', 'url' => route('services.ecommerce')],
            ['title' => 'SEO & Marketing', 'description' => 'Data-driven growth strategies', 'icon' => '🔍', 'url' => route('services.seo')],
        ],
    ];
@endphp

@extends('frontend.services.show', ['service' => $service])