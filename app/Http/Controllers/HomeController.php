<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get services
        $services = Service::where('is_active', true)
            ->limit(6)
            ->get();

        // Get portfolios
        $portfolios = Portfolio::where('is_featured', true)
            ->limit(6)
            ->get();

        // Get testimonials - MAKE SURE THIS RETURNS A COLLECTION
        $testimonials = Testimonial::where('is_active', true)
            ->limit(6)
            ->get();

        // Get blog posts
        $blogPosts = BlogPost::where('is_published', true)
            ->whereNull('deleted_at')
            ->limit(3)
            ->get();

        return view('frontend.home.index', compact(
            'services',
            'portfolios',
            'testimonials',
            'blogPosts'
        ));
    }
}