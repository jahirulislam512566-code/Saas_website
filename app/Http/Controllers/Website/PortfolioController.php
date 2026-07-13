<?php
// app/Http/Controllers/Website/PortfolioController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\PortfolioTag;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display a listing of portfolio items.
     */
    public function index(Request $request)
    {
        $query = Portfolio::with(['category', 'tags', 'images'])
            ->where('is_active', true)
            ->where('is_published', true);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by tag
        if ($request->has('tag') && $request->tag != '') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $projects = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = PortfolioCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $tags = PortfolioTag::whereHas('portfolios', function ($q) {
                $q->where('is_active', true)
                  ->where('is_published', true);
            })
            ->orderBy('name')
            ->get();

        $featuredProjects = Portfolio::with(['category', 'images'])
            ->where('is_active', true)
            ->where('is_published', true)
            ->where('is_featured', true)
            ->limit(3)
            ->get();

        return view('website.portfolio.index', compact('projects', 'categories', 'tags', 'featuredProjects'));
    }

    /**
     * Display the specified portfolio item.
     */
    public function show($slug)
    {
        $project = Portfolio::with(['category', 'tags', 'images', 'testimonials', 'technologies'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment view count
        $project->increment('views');

        // Related projects
        $relatedProjects = Portfolio::with(['category', 'images'])
            ->where('category_id', $project->category_id)
            ->where('id', '!=', $project->id)
            ->where('is_active', true)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        // Project stats
        $stats = [
            'duration' => $project->duration ?? '3 months',
            'team_size' => $project->team_size ?? '5',
            'technologies' => $project->technologies ?? ['React', 'Laravel', 'AWS'],
            'results' => $project->results ?? 'Increased efficiency by 40%',
        ];

        return view('website.portfolio.show', compact('project', 'relatedProjects', 'stats'));
    }

    /**
     * Display portfolio items by category.
     */
    public function category($slug)
    {
        $category = PortfolioCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $projects = Portfolio::with(['category', 'tags', 'images'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->paginate(9);

        return view('website.portfolio.category', compact('category', 'projects'));
    }

    /**
     * Display portfolio items by tag.
     */
    public function tag($slug)
    {
        $tag = PortfolioTag::where('slug', $slug)->firstOrFail();

        $projects = Portfolio::with(['category', 'tags', 'images'])
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('portfolio_tags.id', $tag->id);
            })
            ->where('is_active', true)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->paginate(9);

        return view('website.portfolio.tag', compact('tag', 'projects'));
    }
}