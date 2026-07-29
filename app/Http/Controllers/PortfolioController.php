<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = Portfolio::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        $categories = PortfolioCategory::where('is_active', true)->get();
        
        return view('frontend.portfolio.index', compact('projects', 'categories'));
    }

    public function category($category)
    {
        $categoryModel = PortfolioCategory::where('slug', $category)->firstOrFail();
        $projects = Portfolio::where('category_id', $categoryModel->id)
            ->where('is_published', true)
            ->paginate(9);
        
        return view('frontend.portfolio.category', compact('categoryModel', 'projects'));
    }

    public function show($slug)
    {
        $project = Portfolio::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $relatedProjects = Portfolio::where('id', '!=', $project->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();
        
        return view('frontend.portfolio.show', compact('project', 'relatedProjects'));
    }

    public function filter(Request $request)
    {
        $query = Portfolio::where('is_published', true);
        
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $projects = $query->paginate(9);
        
        return response()->json($projects);
    }
}