{{-- resources/views/frontend/services/ecommerce.blade.php --}}
@php
    $service = [
        'title' => 'E-Commerce Solutions',
        'description' => 'We build powerful, scalable e-commerce platforms that drive sales and deliver exceptional shopping experiences.',
        'icon' => '🛒',
        'features' => [
            'Custom e-commerce development',
            'Payment gateway integration',
            'Shopping cart optimization',
            'Product management systems',
            'Inventory management',
            'Order fulfillment integration',
            'Analytics and reporting',
            'Mobile-optimized shopping',
        ],
        'process' => [
            ['number' => '01', 'title' => 'Strategy', 'description' => 'We define your e-commerce goals.'],
            ['number' => '02', 'title' => 'Design', 'description' => 'We create the shopping experience.'],
            ['number' => '03', 'title' => 'Develop', 'description' => 'We build your online store.'],
            ['number' => '04', 'title' => 'Launch', 'description' => 'We go live and monitor performance.'],
        ],
        'faqs' => [
            ['question' => 'What e-commerce platforms do you support?', 'answer' => 'We work with Shopify, WooCommerce, Magento, and custom solutions built from scratch.'],
            ['question' => 'Do you integrate with existing systems?', 'answer' => 'Yes, we can integrate with your existing ERP, CRM, and other business systems.'],
        ],
        'relatedServices' => [
            ['title' => 'Web Development', 'description' => 'Custom web applications', 'icon' => '💻', 'url' => route('services.web-development')],
            ['title' => 'Web Design', 'description' => 'Beautiful designs', 'icon' => '🎨', 'url' => route('services.web-design')],
            ['title' => 'SEO & Marketing', 'description' => 'Growth strategies', 'icon' => '🔍', 'url' => route('services.seo')],
        ],
    ];
@endphp

@extends('frontend.services.show', ['service' => $service])