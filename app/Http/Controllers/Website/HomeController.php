<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Portfolio;
use App\Models\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Use orderBy('created_at', 'desc') instead of latest() 
        // if you prefer explicit control, though latest() is fine.
        $posts = Post::where('status', 'published')
            ->latest()
            ->limit(3)
            ->get();
            
        // Replaced ->ordered() with ->orderBy('order', 'asc')
        // Assumes you have an 'order' column in your services table
        $services = Service::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->limit(6)
            ->get();
            
        $testimonials = Testimonial::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();
            
        $portfolios = Portfolio::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        // Replaced ->ordered() with ->orderBy('order', 'asc')
       $plans = Plan::where('is_active', 1)
             ->where('is_featured', 1)
             ->orderBy('sort_order', 'asc') // <--- Change to this
             ->limit(3)
             ->get();

        return view('website.home', compact(
            'posts',
            'services',
            'testimonials',
            'portfolios',
            'plans'
        ));
    }
}