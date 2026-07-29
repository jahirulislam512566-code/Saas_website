<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        return view('frontend.pages.sitemap');
    }

    public function xml()
    {
        $posts = BlogPost::where('is_published', true)->get();
        $projects = Portfolio::where('is_published', true)->get();
        $services = Service::where('is_active', true)->get();
        
        return response()->view('sitemap.xml', compact('posts', 'projects', 'services'))
            ->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Sitemap: " . route('sitemap.xml') . "\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /dashboard/\n";
        $content .= "Disallow: /profile/\n";
        
        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}