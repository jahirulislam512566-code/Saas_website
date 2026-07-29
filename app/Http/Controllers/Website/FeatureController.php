<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of features.
     */
    public function index()
    {
        $features = [
            [
                'icon' => '🎨',
                'title' => 'Drag & Drop Builder',
                'description' => 'Build pages visually with our intuitive drag and drop interface. No coding needed.',
                'slug' => 'drag-drop-builder'
            ],
            [
                'icon' => '📱',
                'title' => 'Responsive Design',
                'description' => 'All websites are mobile-friendly and look great on any device.',
                'slug' => 'responsive-design'
            ],
            [
                'icon' => '📝',
                'title' => 'Blog CMS',
                'description' => 'Powerful blogging platform with categories, tags, and SEO optimization.',
                'slug' => 'blog-cms'
            ],
            [
                'icon' => '🤖',
                'title' => 'AI Writer',
                'description' => 'Generate high-quality content instantly with our AI-powered writing assistant.',
                'slug' => 'ai-writer'
            ],
            [
                'icon' => '📊',
                'title' => 'Analytics Dashboard',
                'description' => 'Track visitors, page views, and conversions with real-time analytics.',
                'slug' => 'analytics-dashboard'
            ],
            [
                'icon' => '🔍',
                'title' => 'SEO Tools',
                'description' => 'Built-in SEO tools to help your website rank higher in search results.',
                'slug' => 'seo-tools'
            ],
            [
                'icon' => '🔒',
                'title' => 'Security & SSL',
                'description' => 'Free SSL certificates, daily backups, and advanced threat protection.',
                'slug' => 'security-ssl'
            ],
            [
                'icon' => '⚡',
                'title' => 'Performance Optimized',
                'description' => 'Lightning-fast websites with CDN integration and image optimization.',
                'slug' => 'performance-optimized'
            ],
        ];

        return view('website.features.index', compact('features'));
    }

    /**
     * Display the specified feature.
     */
    public function show($slug)
    {
        $features = [
            'drag-drop-builder' => [
                'title' => 'Drag & Drop Builder',
                'description' => 'Build pages visually with our intuitive drag and drop interface.',
                'benefits' => [
                    'No coding required',
                    'Real-time preview',
                    'Pre-built components',
                    'Customizable templates'
                ]
            ],
            'responsive-design' => [
                'title' => 'Responsive Design',
                'description' => 'All websites are mobile-friendly and look great on any device.',
                'benefits' => [
                    'Mobile-first approach',
                    'Tablet optimization',
                    'Desktop excellence',
                    'All screen sizes supported'
                ]
            ],
            // Add more features as needed
        ];

        $feature = $features[$slug] ?? null;

        if (!$feature) {
            abort(404);
        }

        return view('website.features.show', compact('feature', 'slug'));
    }

    /**
     * Display features by category.
     */
    public function category($category)
    {
        // Filter features by category
        return view('website.features-category', compact('category'));
    }
}