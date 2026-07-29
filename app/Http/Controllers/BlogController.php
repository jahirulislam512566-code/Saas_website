<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);
        
        $featuredPost = BlogPost::where('is_published', true)
            ->where('is_featured', true)
            ->first();
        
        $categories = BlogCategory::where('is_active', true)->get();
        $tags = BlogTag::where('is_active', true)->get();
        
        return view('frontend.blog.index', compact('posts', 'featuredPost', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $relatedPosts = BlogPost::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->where('category_id', $post->category_id)
            ->limit(3)
            ->get();
        
        return view('frontend.blog.show', compact('post', 'relatedPosts'));
    }

    public function category($category)
    {
        $categoryModel = BlogCategory::where('slug', $category)->firstOrFail();
        $posts = BlogPost::where('category_id', $categoryModel->id)
            ->where('is_published', true)
            ->paginate(9);
        
        return view('frontend.blog.category', compact('categoryModel', 'posts'));
    }

    public function tag($tag)
    {
        $tagModel = BlogTag::where('slug', $tag)->firstOrFail();
        $posts = $tagModel->posts()
            ->where('is_published', true)
            ->paginate(9);
        
        return view('frontend.blog.tag', compact('tagModel', 'posts'));
    }

    public function author($author)
    {
        $posts = BlogPost::where('author_slug', $author)
            ->where('is_published', true)
            ->paginate(9);
        
        return view('frontend.blog.author', compact('posts', 'author'));
    }

    public function archive($year, $month)
    {
        $posts = BlogPost::whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->where('is_published', true)
            ->paginate(9);
        
        return view('frontend.blog.archive', compact('posts', 'year', 'month'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $posts = BlogPost::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%')
                  ->orWhere('excerpt', 'like', '%' . $query . '%');
            })
            ->paginate(9);
        
        return view('frontend.blog.search', compact('posts', 'query'));
    }

    public function popular()
    {
        $posts = BlogPost::where('is_published', true)
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($posts);
    }

    public function recent()
    {
        $posts = BlogPost::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($posts);
    }
}