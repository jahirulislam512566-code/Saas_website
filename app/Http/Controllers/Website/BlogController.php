<?php
// app/Http/Controllers/Website/BlogController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author', 'tags'])
            ->where('is_published', true)
            ->where('published_at', '<=', now());

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag') && $request->tag != '') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%");
            });
        }

        $posts = $query->orderBy('published_at', 'desc')
            ->paginate(9);

        // Featured post
        $featuredPost = Post::with(['category', 'author', 'tags'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->where('published_at', '<=', now())
            ->first();

        // Categories for sidebar
        $categories = Category::whereHas('posts', function ($q) {
                $q->where('is_published', true)
                  ->where('published_at', '<=', now());
            })
            ->withCount(['posts' => function ($q) {
                $q->where('is_published', true)
                  ->where('published_at', '<=', now());
            }])
            ->orderBy('posts_count', 'desc')
            ->get();

        // Tags for sidebar
        $tags = Tag::whereHas('posts', function ($q) {
                $q->where('is_published', true)
                  ->where('published_at', '<=', now());
            })
            ->withCount(['posts' => function ($q) {
                $q->where('is_published', true)
                  ->where('published_at', '<=', now());
            }])
            ->orderBy('posts_count', 'desc')
            ->limit(20)
            ->get();

        // Popular posts
        $popularPosts = Post::with(['category', 'author'])
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return view('website.blog.index', compact(
            'posts',
            'featuredPost',
            'categories',
            'tags',
            'popularPosts'
        ));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = Post::with(['category', 'author', 'tags', 'comments' => function ($q) {
                $q->where('is_approved', true)
                  ->where('is_spam', false)
                  ->orderBy('created_at', 'desc');
            }])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment view count
        $post->increment('views');

        // Related posts
        $relatedPosts = Post::with(['category', 'author'])
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->limit(3)
            ->get();

        // Previous/Next posts
        $previousPost = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        return view('website.blog.show', compact(
            'post',
            'relatedPosts',
            'previousPost',
            'nextPost'
        ));
    }

    /**
     * Display posts by category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['category', 'author', 'tags'])
            ->where('category_id', $category->id)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('website.blog.category', compact('category', 'posts'));
    }

    /**
     * Display posts by tag.
     */
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['category', 'author', 'tags'])
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('website.blog.tag', compact('tag', 'posts'));
    }

    /**
     * Display posts by author.
     */
    public function author($id)
    {
        $author = User::findOrFail($id);

        $posts = Post::with(['category', 'author', 'tags'])
            ->where('author_id', $id)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('website.blog.author', compact('author', 'posts'));
    }

    /**
     * Store a new comment.
     */
    public function storeComment(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|min:3',
            'g-recaptcha-response' => 'sometimes|required|recaptcha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $post = Post::findOrFail($postId);

        $comment = Comment::create([
            'post_id' => $post->id,
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'is_approved' => true, // Auto-approve or set to false for moderation
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notify admin about new comment
        // Mail::to('admin@saashub.com')->send(new NewCommentMail($comment));

        return redirect()->back()
            ->with('success', 'Your comment has been submitted successfully!');
    }

    /**
     * Search blog posts.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $posts = Post::with(['category', 'author', 'tags'])
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('website.blog.search', compact('posts', 'query'));
    }

    /**
     * Subscribe to newsletter.
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Create subscription
        // NewsletterSubscriber::create(['email' => $request->email]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to our newsletter!',
        ]);
    }
}