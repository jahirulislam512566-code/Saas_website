<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Page;
use App\Models\Post;
use App\Models\Template;
use App\Models\Media;
use Illuminate\Http\Request;

class PolicyExampleController extends Controller
{
    public function indexWebsites(Request $request)
    {
        $this->authorize('viewAny', Website::class);
        
        $websites = $request->user()->websites()->paginate(15);
        return view('websites.index', compact('websites'));
    }

    public function showWebsite(Website $website)
    {
        $this->authorize('view', $website);
        return view('websites.show', compact('website'));
    }

    public function createWebsite(Request $request)
    {
        $this->authorize('create', Website::class);
        return view('websites.create');
    }

    public function storeWebsite(Request $request)
    {
        $this->authorize('create', Website::class);
        // Store website logic
    }

    public function editWebsite(Website $website)
    {
        $this->authorize('update', $website);
        return view('websites.edit', compact('website'));
    }

    public function updateWebsite(Request $request, Website $website)
    {
        $this->authorize('update', $website);
        // Update website logic
    }

    public function deleteWebsite(Website $website)
    {
        $this->authorize('delete', $website);
        // Delete website logic
    }

    public function publishWebsite(Website $website)
    {
        $this->authorize('publish', $website);
        // Publish website logic
    }

    public function cloneWebsite(Website $website)
    {
        $this->authorize('clone', $website);
        // Clone website logic
    }

    public function manageSettings(Website $website)
    {
        $this->authorize('manageSettings', $website);
        return view('websites.settings', compact('website'));
    }

    public function viewAnalytics(Website $website)
    {
        $this->authorize('viewAnalytics', $website);
        return view('websites.analytics', compact('website'));
    }

    public function indexPages(Website $website)
    {
        $this->authorize('viewAny', [Page::class, $website]);
        $pages = $website->pages()->paginate(15);
        return view('pages.index', compact('website', 'pages'));
    }

    public function createPage(Website $website)
    {
        $this->authorize('create', [Page::class, $website]);
        return view('pages.create', compact('website'));
    }

    public function editPage(Page $page)
    {
        $this->authorize('update', $page);
        return view('pages.edit', compact('page'));
    }

    public function managePageSections(Page $page)
    {
        $this->authorize('manageSections', $page);
        return view('pages.sections', compact('page'));
    }

    public function indexPosts(Website $website)
    {
        $this->authorize('viewAny', [Post::class, $website]);
        $posts = $website->posts()->paginate(15);
        return view('posts.index', compact('website', 'posts'));
    }

    public function createPost(Website $website)
    {
        $this->authorize('create', [Post::class, $website]);
        return view('posts.create', compact('website'));
    }

    public function editPost(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function indexTemplates()
    {
        $this->authorize('viewAny', Template::class);
        $templates = Template::paginate(15);
        return view('templates.index', compact('templates'));
    }

    public function installTemplate(Template $template)
    {
        $this->authorize('install', $template);
        // Install template logic
    }

    public function indexMedia(Website $website)
    {
        $this->authorize('viewAny', [Media::class, $website]);
        $media = $website->media()->paginate(20);
        return view('media.index', compact('website', 'media'));
    }

    public function uploadMedia(Request $request, Website $website)
    {
        $this->authorize('create', [Media::class, $website]);
        // Upload media logic
    }

    public function deleteMedia(Media $media)
    {
        $this->authorize('delete', $media);
        // Delete media logic
    }
}