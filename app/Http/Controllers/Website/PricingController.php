<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Display the pricing page.
     */
    public function index()
    {
        $plans = [
            [
                'name' => 'Starter',
                'price' => 19,
                'description' => 'Perfect for getting started',
                'features' => [
                    '1 Website',
                    '10 GB Storage',
                    '5 Pages',
                    'Free SSL Certificate',
                    'Basic SEO Tools',
                    'Email Support'
                ],
                'button_text' => 'Start Free Trial',
                'popular' => false
            ],
            [
                'name' => 'Business',
                'price' => 49,
                'description' => 'For growing businesses',
                'features' => [
                    '10 Websites',
                    '50 GB Storage',
                    'Unlimited Pages',
                    'Free SSL Certificate',
                    'Advanced SEO Tools',
                    'Priority Support',
                    'Custom Domain',
                    'AI Content Assistant'
                ],
                'button_text' => 'Start Free Trial',
                'popular' => true
            ],
            [
                'name' => 'Agency',
                'price' => 99,
                'description' => 'For large organizations',
                'features' => [
                    'Unlimited Websites',
                    '500 GB Storage',
                    'Unlimited Pages',
                    'Free SSL Certificate',
                    'Advanced SEO Tools',
                    '24/7 Priority Support',
                    'Multiple Custom Domains',
                    'AI Content Assistant',
                    'White Label',
                    'API Access'
                ],
                'button_text' => 'Contact Sales',
                'popular' => false
            ],
        ];

        return view('website.pricing', compact('plans'));
    }

    /**
     * Compare pricing plans.
     */
    public function compare()
    {
        $plans = [
            'Starter' => [
                'websites' => 1,
                'storage' => '10 GB',
                'pages' => 5,
                'ssl' => true,
                'seo' => 'Basic',
                'support' => 'Email',
                'custom_domain' => false,
                'ai_assistant' => false,
            ],
            'Business' => [
                'websites' => 10,
                'storage' => '50 GB',
                'pages' => 'Unlimited',
                'ssl' => true,
                'seo' => 'Advanced',
                'support' => 'Priority',
                'custom_domain' => true,
                'ai_assistant' => true,
            ],
            'Agency' => [
                'websites' => 'Unlimited',
                'storage' => '500 GB',
                'pages' => 'Unlimited',
                'ssl' => true,
                'seo' => 'Advanced',
                'support' => '24/7 Priority',
                'custom_domain' => true,
                'ai_assistant' => true,
            ],
        ];

        return view('website.pricing-compare', compact('plans'));
    }
}