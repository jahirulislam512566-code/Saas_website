<?php
// app/Http/Controllers/Website/ServiceController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        $services = Service::with(['category', 'features'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(12);

        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $featuredServices = Service::with(['category', 'features'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('website.services.index', compact('services', 'categories', 'featuredServices'));
    }

    /**
     * Display the specified service.
     */
    public function show($slug)
    {
        $service = Service::with(['category', 'features', 'testimonials', 'faqs'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $service->increment('views');

        // Related services
        $relatedServices = Service::with(['category'])
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();

        // Service process steps
        $processSteps = $service->processSteps ?? [
            (object) ['title' => 'Discovery', 'description' => 'We understand your needs and goals.', 'icon' => 'fa-lightbulb'],
            (object) ['title' => 'Planning', 'description' => 'We create a detailed plan and timeline.', 'icon' => 'fa-clipboard-list'],
            (object) ['title' => 'Execution', 'description' => 'We build and deliver your solution.', 'icon' => 'fa-cogs'],
            (object) ['title' => 'Support', 'description' => 'We provide ongoing support and maintenance.', 'icon' => 'fa-headset'],
        ];

        return view('website.services.show', compact('service', 'relatedServices', 'processSteps'));
    }

    /**
     * Display services by category.
     */
    public function category($slug)
    {
        $category = ServiceCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $services = Service::with(['category', 'features'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(12);

        return view('website.services.category', compact('category', 'services'));
    }

    /**
     * Search services.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $services = Service::with(['category', 'features'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%");
            })
            ->orderBy('sort_order')
            ->paginate(12);

        return view('website.services.search', compact('services', 'query'));
    }
}