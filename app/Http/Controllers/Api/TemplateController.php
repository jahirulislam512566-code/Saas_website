<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = Template::where('is_active', true)
            ->when($request->category, function ($query, $category) {
                return $query->where('category_slug', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->limit($request->limit ?? 20)
            ->get()
            ->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'category' => $template->category,
                    'category_slug' => $template->category_slug,
                    'description' => $template->description,
                    'image' => $template->image_url,
                    'pages' => $template->pages ?? 6,
                    'price' => $template->price == 0 ? 'Free' : '$' . $template->price,
                    'features' => $template->features ?? ['Responsive', 'SEO Optimized'],
                    'colors' => $template->colors ?? ['#4F46E5', '#7C3AED'],
                    'rating' => $template->rating ?? 4.5,
                    'reviews' => $template->reviews ?? 0,
                    'used_by' => $template->usage_count ?? 0,
                    'popular' => $template->popular ?? false,
                    'is_free' => $template->price == 0,
                    'is_new' => $template->created_at->diffInDays(now()) < 30,
                ];
            });

        return response()->json($templates);
    }

    public function show($id)
    {
        $template = Template::findOrFail($id);
        
        return response()->json([
            'id' => $template->id,
            'name' => $template->name,
            'category' => $template->category,
            'category_slug' => $template->category_slug,
            'description' => $template->description,
            'image' => $template->image_url,
            'pages' => $template->pages ?? 6,
            'price' => $template->price == 0 ? 'Free' : '$' . $template->price,
            'features' => $template->features ?? ['Responsive', 'SEO Optimized', 'Fast Loading'],
            'colors' => $template->colors ?? ['#4F46E5', '#7C3AED', '#EC4899'],
            'rating' => $template->rating ?? 4.5,
            'reviews' => $template->reviews ?? 0,
            'used_by' => $template->usage_count ?? 0,
            'popular' => $template->popular ?? false,
            'is_free' => $template->price == 0,
            'is_new' => $template->created_at->diffInDays(now()) < 30,
            'demo_url' => $template->demo_url,
        ]);
    }

    public function categories()
    {
        $categories = Template::where('is_active', true)
            ->groupBy('category_slug')
            ->select('category_slug', 'category')
            ->selectRaw('count(*) as count')
            ->get();

        return response()->json($categories);
    }
}