<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = [
            [
                'title' => 'Website SEO Guide',
                'type' => 'Guide',
                'description' => 'Complete guide to optimize your website for search engines',
                'icon' => '📖',
                'slug' => 'website-seo-guide'
            ],
            [
                'title' => 'Website Launch Checklist',
                'type' => 'Checklist',
                'description' => 'Ensure everything is ready before launching your website',
                'icon' => '✅',
                'slug' => 'website-launch-checklist'
            ],
            [
                'title' => 'Free Icons',
                'type' => 'Download',
                'description' => 'High-quality icons for your website designs',
                'icon' => '🎨',
                'slug' => 'free-icons'
            ],
            [
                'title' => 'Free Images',
                'type' => 'Download',
                'description' => 'Royalty-free images for your website',
                'icon' => '🖼️',
                'slug' => 'free-images'
            ],
            [
                'title' => 'Website Planning Template',
                'type' => 'Template',
                'description' => 'Plan your website structure and content',
                'icon' => '📋',
                'slug' => 'website-planning-template'
            ],
        ];

        return view('website.resources', compact('resources'));
    }

    public function category($category)
    {
        return view('website.resources-category', compact('category'));
    }

    public function show($slug)
    {
        return view('website.resource-detail', compact('slug'));
    }
}