<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        $categories = ServiceCategory::where('is_active', true)->get();
        
        return view('frontend.services.index', compact('services', 'categories'));
    }

    public function webDesign()
    {
        $service = Service::where('slug', 'web-design')->firstOrFail();
        return view('frontend.services.web-design', compact('service'));
    }

    public function webDevelopment()
    {
        $service = Service::where('slug', 'web-development')->firstOrFail();
        return view('frontend.services.web-development', compact('service'));
    }

    public function ecommerce()
    {
        $service = Service::where('slug', 'ecommerce')->firstOrFail();
        return view('frontend.services.ecommerce', compact('service'));
    }

    public function seo()
    {
        $service = Service::where('slug', 'seo')->firstOrFail();
        return view('frontend.services.seo', compact('service'));
    }

    public function uiuxDesign()
    {
        $service = Service::where('slug', 'uiux-design')->firstOrFail();
        return view('frontend.services.uiux-design', compact('service'));
    }

    public function maintenance()
    {
        $service = Service::where('slug', 'maintenance')->firstOrFail();
        return view('frontend.services.maintenance', compact('service'));
    }

    public function hosting()
    {
        $service = Service::where('slug', 'hosting')->firstOrFail();
        return view('frontend.services.hosting', compact('service'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        $relatedServices = Service::where('id', '!=', $service->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();
        
        return view('frontend.services.show', compact('service', 'relatedServices'));
    }
}