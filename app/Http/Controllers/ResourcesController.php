<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    /**
     * Display the FAQ page.
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'What services do you offer?',
                'answer' => 'We offer a comprehensive range of digital services including UI/UX design, web development, mobile app development, digital strategy, SEO and marketing, and cybersecurity solutions. Our team of experts can help you at any stage of your digital journey.'
            ],
            [
                'question' => 'How long does a typical project take?',
                'answer' => 'Project timelines vary depending on the scope and complexity. A typical website redesign might take 6-12 weeks, while a custom application development project could take 3-6 months. We provide detailed timelines during our initial consultation.'
            ],
            [
                'question' => 'What is your pricing model?',
                'answer' => 'We offer flexible pricing models including fixed-price projects, hourly rates, and retainer arrangements. We work with you to find the best option that fits your budget and project requirements.'
            ],
            [
                'question' => 'Do you offer ongoing support?',
                'answer' => 'Yes, we offer various support and maintenance packages to ensure your digital assets remain secure, up-to-date, and performing optimally. Our support plans include regular updates, security patches, and priority assistance.'
            ],
            [
                'question' => 'Can you help with a complete digital transformation?',
                'answer' => 'Absolutely. We specialize in end-to-end digital transformation services. From strategy development and planning to execution and optimization, we can help guide your organization through every step of the process.'
            ],
            [
                'question' => 'What industries do you work with?',
                'answer' => 'We work with clients across various industries including technology, healthcare, finance, e-commerce, education, non-profits, and more. Our diverse experience allows us to bring best practices from different sectors to your project.'
            ],
            [
                'question' => 'Do you sign NDAs?',
                'answer' => 'Yes, we regularly sign Non-Disclosure Agreements and are happy to work with your legal team to ensure your intellectual property and confidential information are protected.'
            ],
            [
                'question' => 'How do you handle project management?',
                'answer' => 'We use agile project management methodologies and tools like Jira, Trello, and Slack to ensure transparent communication and efficient project delivery. You\'ll have a dedicated project manager and regular status updates.'
            ],
        ];

        return view('frontend.pages.faq', compact('faqs'));
    }

    /**
     * Display the documentation page.
     *
     * @return \Illuminate\View\View
     */
    public function documentation()
    {
        $documentation = [
            'getting_started' => [
                'title' => 'Getting Started',
                'items' => [
                    ['title' => 'Installation Guide', 'description' => 'Step-by-step guide to get started with our platform.'],
                    ['title' => 'System Requirements', 'description' => 'Minimum requirements for running our applications.'],
                    ['title' => 'Quick Start Tutorial', 'description' => 'Learn the basics in 15 minutes.'],
                    ['title' => 'Configuration Guide', 'description' => 'Configure your application settings.'],
                ]
            ],
            'development' => [
                'title' => 'Development',
                'items' => [
                    ['title' => 'API Reference', 'description' => 'Complete API documentation and endpoints.'],
                    ['title' => 'Code Examples', 'description' => 'Practical code examples for common tasks.'],
                    ['title' => 'Best Practices', 'description' => 'Recommended development practices and patterns.'],
                    ['title' => 'Testing Guide', 'description' => 'How to test your applications effectively.'],
                ]
            ],
            'deployment' => [
                'title' => 'Deployment',
                'items' => [
                    ['title' => 'Deployment Guide', 'description' => 'Step-by-step deployment instructions.'],
                    ['title' => 'Environment Setup', 'description' => 'Configure your production environment.'],
                    ['title' => 'Performance Optimization', 'description' => 'Tips for optimizing performance.'],
                    ['title' => 'Security Checklist', 'description' => 'Ensure your application is secure.'],
                ]
            ],
            'support' => [
                'title' => 'Support & Maintenance',
                'items' => [
                    ['title' => 'Troubleshooting Guide', 'description' => 'Common issues and their solutions.'],
                    ['title' => 'FAQ', 'description' => 'Frequently asked questions about our platform.'],
                    ['title' => 'Contact Support', 'description' => 'How to get help from our support team.'],
                    ['title' => 'Service Status', 'description' => 'Real-time status of our services.'],
                ]
            ],
        ];

        return view('frontend.pages.documentation', compact('documentation'));
    }

    /**
     * Display the case studies page.
     *
     * @return \Illuminate\View\View
     */
    public function caseStudies()
    {
        $caseStudies = [
            [
                'title' => 'E-Commerce Transformation',
                'client' => 'RetailCo',
                'industry' => 'Retail',
                'description' => 'Complete digital transformation of a traditional retail business.',
                'challenge' => 'The client needed to modernize their online presence and compete with digital-native competitors.',
                'solution' => 'We built a custom e-commerce platform with AI-powered recommendations and seamless payment integration.',
                'results' => [
                    '300% increase in online sales',
                    '50% reduction in cart abandonment',
                    '4x faster page load times',
                ],
                'image' => asset('images/case-studies/ecommerce.jpg'),
                'link' => '#',
            ],
            [
                'title' => 'Healthcare Platform',
                'client' => 'MediCare Plus',
                'industry' => 'Healthcare',
                'description' => 'A comprehensive healthcare management platform.',
                'challenge' => 'The client needed a secure, HIPAA-compliant platform for patient management.',
                'solution' => 'We developed a secure platform with telemedicine features and patient portal.',
                'results' => [
                    '40% improvement in patient engagement',
                    '60% reduction in administrative overhead',
                    '99.9% uptime achieved',
                ],
                'image' => asset('images/case-studies/healthcare.jpg'),
                'link' => '#',
            ],
            [
                'title' => 'FinTech Mobile App',
                'client' => 'FinSmart',
                'industry' => 'Finance',
                'description' => 'A mobile banking app with advanced features.',
                'challenge' => 'The client wanted to disrupt traditional banking with a modern mobile experience.',
                'solution' => 'We built a React Native app with biometric authentication and real-time transactions.',
                'results' => [
                    '1M+ downloads',
                    '4.8/5 app store rating',
                    '200% user growth in 6 months',
                ],
                'image' => asset('images/case-studies/fintech.jpg'),
                'link' => '#',
            ],
        ];

        return view('frontend.pages.case-studies', compact('caseStudies'));
    }

    /**
     * Display the downloads page.
     *
     * @return \Illuminate\View\View
     */
    public function downloads()
    {
        $downloads = [
            'brochures' => [
                'title' => 'Brochures & Catalogs',
                'items' => [
                    ['title' => 'Company Brochure', 'description' => 'Overview of our services and capabilities.', 'size' => '2.5 MB', 'icon' => 'fa-file-pdf', 'url' => '#'],
                    ['title' => 'Web Design Portfolio', 'description' => 'Our best web design projects.', 'size' => '5.1 MB', 'icon' => 'fa-file-pdf', 'url' => '#'],
                    ['title' => 'Case Studies Catalog', 'description' => 'Detailed case studies from various industries.', 'size' => '8.3 MB', 'icon' => 'fa-file-pdf', 'url' => '#'],
                ]
            ],
            'white_papers' => [
                'title' => 'White Papers & Reports',
                'items' => [
                    ['title' => 'Digital Transformation Guide', 'description' => 'Strategic insights for digital transformation.', 'size' => '3.2 MB', 'icon' => 'fa-file-pdf', 'url' => '#'],
                    ['title' => 'E-Commerce Trends 2024', 'description' => 'Latest trends in e-commerce.', 'size' => '2.8 MB', 'icon' => 'fa-file-pdf', 'url' => '#'],
                    ['title' => 'UX Research Report', 'description' => 'User experience research findings and insights.', 'size' => '4.1 MB', 'icon' => 'fa-file-pdf', 'url' => '#'],
                ]
            ],
            'templates' => [
                'title' => 'Templates & Resources',
                'items' => [
                    ['title' => 'Website Design Template', 'description' => 'Professional website template for startups.', 'size' => '15 MB', 'icon' => 'fa-file-archive', 'url' => '#'],
                    ['title' => 'Marketing Kit', 'description' => 'Complete marketing template kit.', 'size' => '12 MB', 'icon' => 'fa-file-archive', 'url' => '#'],
                    ['title' => 'Brand Guidelines Template', 'description' => 'Customizable brand guidelines template.', 'size' => '8 MB', 'icon' => 'fa-file-archive', 'url' => '#'],
                ]
            ],
            'media_assets' => [
                'title' => 'Media Assets',
                'items' => [
                    ['title' => 'Logo Assets', 'description' => 'High-resolution logos in various formats.', 'size' => '20 MB', 'icon' => 'fa-file-image', 'url' => '#'],
                    ['title' => 'Presentation Template', 'description' => 'Professional PowerPoint template.', 'size' => '10 MB', 'icon' => 'fa-file-powerpoint', 'url' => '#'],
                    ['title' => 'Social Media Assets', 'description' => 'Social media graphics and templates.', 'size' => '25 MB', 'icon' => 'fa-file-image', 'url' => '#'],
                ]
            ],
        ];

        return view('frontend.pages.downloads', compact('downloads'));
    }
}