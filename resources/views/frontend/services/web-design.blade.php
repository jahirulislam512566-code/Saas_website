{{-- resources/views/frontend/services/web-design.blade.php --}}
@php
    $service = [
        'title' => 'Web Design',
        'description' => 'We create beautiful, intuitive designs that users love. Our user-centered approach ensures that every design decision is driven by research and user needs.',
        'icon' => '🎨',
        'features' => [
            'User research and analysis',
            'Wireframing and prototyping',
            'UI/UX design',
            'Design systems development',
            'Responsive design',
            'Accessibility compliance',
            'Brand identity integration',
            'User testing and iteration',
        ],
        'process' => [
            ['number' => '01', 'title' => 'Research', 'description' => 'We analyze your users and market.'],
            ['number' => '02', 'title' => 'Design', 'description' => 'We create wireframes and prototypes.'],
            ['number' => '03', 'title' => 'Test', 'description' => 'We validate with real users.'],
            ['number' => '04', 'title' => 'Refine', 'description' => 'We polish and finalize designs.'],
        ],
        'faqs' => [
            ['question' => 'How do you approach design?', 'answer' => 'We follow a user-centered design process that begins with research and continues through testing and iteration.'],
            ['question' => 'What tools do you use?', 'answer' => 'We use industry-standard tools including Figma, Sketch, Adobe XD, and various prototyping tools.'],
        ],
        'relatedServices' => [
            ['title' => 'Web Development', 'description' => 'Custom web applications', 'icon' => '💻', 'url' => route('services.web-development')],
            ['title' => 'E-Commerce', 'description' => 'Powerful online stores', 'icon' => '🛒', 'url' => route('services.ecommerce')],
            ['title' => 'Maintenance', 'description' => 'Ongoing support', 'icon' => '🔧', 'url' => route('services.maintenance')],
        ],
    ];
@endphp

@extends('frontend.services.show', ['service' => $service])