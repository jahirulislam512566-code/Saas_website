<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
public function index(Request $request)
{
    $tenantId = Auth::user()->tenant_id;

    $query = Post::with(['user', 'category', 'tags'])
        ->when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        });

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by category
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    // Main posts
    $posts = $query->latest()->paginate(15);
    
    // Dashboard widgets
    $recentPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->latest()
        ->take(5)
        ->get();
    
    // Category statistics
    $categoryStats = Category::withCount(['posts' => function ($q) use ($tenantId) {
        $q->when($tenantId, function ($query) use ($tenantId) {
            return $query->where('tenant_id', $tenantId);
        });
    }])->having('posts_count', '>', 0)
        ->orderBy('posts_count', 'desc')
        ->get();
    
    // ✅ ADD THIS: Top posts by views
    $topPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->where('status', 'published')
        ->orderBy('views', 'desc')
        ->limit(5)
        ->get();
    
    // Stats
    $totalPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->count();
    
    $publishedCount = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->where('status', 'published')->count();
    
    $draftCount = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->where('status', 'draft')->count();
    
    // Lists
    $categories = Category::all();
    $statuses = ['draft', 'published', 'archived'];

    // ✅ ADD $topPosts to compact()
    return view('admin.blog.posts.index', compact(
        'posts',
        'recentPosts',
        'categoryStats',
        'topPosts',
        'totalPosts',
        'publishedCount',
        'draftCount',
        'categories',
        'statuses'
    ));
}
    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.blog.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'featured_image' => ['nullable', 'exists:media,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        $tenantId = Auth::user()->tenant_id;

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $post = Post::create([
            'tenant_id' => $tenantId,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'category_id' => $validated['category_id'],
            'featured_image' => $validated['featured_image'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' 
                ? ($validated['published_at'] ?? now()) 
                : null,
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160),
        ]);

        // Attach tags
        if (!empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'category', 'tags', 'comments']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post->load('tags');
        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $post->id],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'featured_image' => ['nullable', 'exists:media,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'category_id' => $validated['category_id'],
            'featured_image' => $validated['featured_image'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' 
                ? ($validated['published_at'] ?? ($post->published_at ?? now())) 
                : null,
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160),
        ]);

        // Sync tags
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Publish a post.
     */
    public function publish(Post $post)
    {
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return back()->with('success', 'Post published successfully.');
    }

    /**
     * Unpublish a post.
     */
    public function unpublish(Post $post)
    {
        $post->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return back()->with('success', 'Post unpublished successfully.');
    }

    /**
     * Archive a post.
     */
    public function archive(Post $post)
    {
        $post->update(['status' => 'archived']);

        return back()->with('success', 'Post archived successfully.');
    }

    /**
     * Duplicate a post.
     */
    public function duplicate(Post $post)
    {
        $newPost = $post->replicate();
        $newPost->title = $post->title . ' (Copy)';
        $newPost->slug = Str::slug($post->title . '-copy');
        $newPost->status = 'draft';
        $newPost->published_at = null;
        $newPost->save();

        // Duplicate tags
        foreach ($post->tags as $tag) {
            $newPost->tags()->attach($tag->id);
        }

        return redirect()->route('admin.blog.posts.edit', $newPost)
            ->with('success', 'Post duplicated successfully.');
    }

    /**
 * Bulk delete posts.
 */
public function bulkDelete(Request $request)
{
    $request->validate([
        'ids' => ['required', 'array'],
        'ids.*' => ['exists:posts,id'],
    ]);

    $tenantId = Auth::user()->tenant_id;
    $deleted = Post::whereIn('id', $request->ids)
        ->when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })
        ->delete();

    return response()->json([
        'success' => true,
        'message' => "{$deleted} posts deleted successfully.",
    ]);
}

/**
 * Bulk publish posts.
 */
public function bulkPublish(Request $request)
{
    $request->validate([
        'ids' => ['required', 'array'],
        'ids.*' => ['exists:posts,id'],
    ]);

    $tenantId = Auth::user()->tenant_id;
    $published = Post::whereIn('id', $request->ids)
        ->when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })
        ->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

    return response()->json([
        'success' => true,
        'message' => "{$published} posts published successfully.",
    ]);
}

/**
 * Export posts to CSV.
 */
public function export(Request $request)
{
    $tenantId = Auth::user()->tenant_id;

    $query = Post::with(['user', 'category'])
        ->when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        });

    // Apply filters
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    if ($request->filled('search')) {
        $query->search($request->search);
    }

    $posts = $query->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename=posts_' . date('Y-m-d') . '.csv',
    ];

    $callback = function () use ($posts) {
        $file = fopen('php://output', 'w');
        
        fputcsv($file, [
            'ID',
            'Title',
            'Slug',
            'Category',
            'Status',
            'Author',
            'Views',
            'Published At',
            'Created At'
        ]);

        foreach ($posts as $post) {
            fputcsv($file, [
                $post->id,
                $post->title,
                $post->slug,
                $post->category->name ?? 'Uncategorized',
                $post->status,
                $post->user->name ?? 'Unknown',
                $post->views ?? 0,
                $post->published_at ? $post->published_at->format('Y-m-d H:i:s') : '',
                $post->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Display posts dashboard.
 */
public function dashboard()
{
    $tenantId = Auth::user()->tenant_id;

    // Get counts
    $totalPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->count();

    $publishedCount = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->where('status', 'published')->count();

    $draftCount = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->where('status', 'draft')->count();

    $totalViews = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->sum('views');

    // Calculate growth (last month vs this month)
    $lastMonth = now()->subMonth();
    $thisMonth = now();

    $lastMonthPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->whereBetween('created_at', [
        $lastMonth->startOfMonth(),
        $lastMonth->endOfMonth()
    ])->count();

    $thisMonthPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->whereBetween('created_at', [
        $thisMonth->startOfMonth(),
        $thisMonth->endOfMonth()
    ])->count();

    $growthPercentage = $lastMonthPosts > 0 
        ? round((($thisMonthPosts - $lastMonthPosts) / $lastMonthPosts) * 100, 1)
        : 0;

    // Get recent posts
    $recentPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->with(['user', 'category', 'featuredImage'])
        ->latest()
        ->limit(10)
        ->get();

    // Get category stats
    $categoryStats = Category::withCount(['posts' => function ($q) use ($tenantId) {
        $q->when($tenantId, function ($query) use ($tenantId) {
            return $query->where('tenant_id', $tenantId);
        });
    }])->having('posts_count', '>', 0)
        ->orderBy('posts_count', 'desc')
        ->get();

    // Get top posts by views
    $topPosts = Post::when($tenantId, function ($q) use ($tenantId) {
        return $q->where('tenant_id', $tenantId);
    })->where('status', 'published')
        ->orderBy('views', 'desc')
        ->limit(5)
        ->get();

    return view('admin.blog.posts.dashboard', compact(
        'totalPosts',
        'publishedCount',
        'draftCount',
        'totalViews',
        'growthPercentage',
        'recentPosts',
        'categoryStats',
        'topPosts'
    ));
}

}