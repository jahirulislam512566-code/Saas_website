<?php
// app/Http/Controllers/Admin/PageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Website;
use App\Models\Template;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Page::forTenant($tenantId)
                ->with(['website', 'sections'])
                ->withCount('sections');

            // Search filter
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Website filter
            if ($request->filled('website_id')) {
                $query->where('website_id', $request->website_id);
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $allowedSorts = ['id', 'title', 'created_at', 'updated_at', 'order'];
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $pages = $query->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Page::forTenant($tenantId)->count(),
                'published' => Page::forTenant($tenantId)->where('status', 'published')->count(),
                'draft' => Page::forTenant($tenantId)->where('status', 'draft')->count(),
                'archived' => Page::forTenant($tenantId)->where('status', 'archived')->count(),
                'home' => Page::forTenant($tenantId)->where('is_home', true)->count(),
            ];

            // Get websites for filter
            $websites = Website::forTenant($tenantId)->get();

            // Get notification variables for top-nav
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.pages.index', compact(
                'pages',
                'websites',
                'stats',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching pages: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch pages. Please try again.');
        }
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $websites = Website::forTenant($tenantId)->get();
            $templates = Template::forTenant($tenantId)->where('is_active', true)->get();
            $parentPages = Page::forTenant($tenantId)->parents()->get();

            return view('admin.pages.form', compact('websites', 'templates', 'parentPages'));
        } catch (\Exception $e) {
            Log::error('Error loading create page form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create page form.');
        }
    }

    /**
     * Store a newly created page.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255', 'unique:pages'],
                'website_id' => ['required', 'exists:websites,id'],
                'template_id' => ['nullable', 'exists:templates,id'],
                'parent_id' => ['nullable', 'exists:pages,id'],
                'content' => ['nullable', 'string'],
                'excerpt' => ['nullable', 'string', 'max:500'],
                'status' => ['required', 'in:draft,published,archived'],
                'is_home' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
                'order' => ['nullable', 'integer', 'min:0'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:500'],
                'meta_keywords' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                // Generate slug if not provided
                $slug = $request->slug;
                if (empty($slug)) {
                    $slug = Str::slug($request->title);
                    $count = Page::where('slug', $slug)->count();
                    if ($count > 0) {
                        $slug = $slug . '-' . ($count + 1);
                    }
                }

                // If this is home page, unset other home pages
                if ($request->has('is_home')) {
                    Page::where('website_id', $request->website_id)
                        ->where('is_home', true)
                        ->update(['is_home' => false]);
                }

                $page = Page::create([
                    'tenant_id' => $tenantId,
                    'website_id' => $request->website_id,
                    'template_id' => $request->template_id,
                    'parent_id' => $request->parent_id,
                    'title' => $request->title,
                    'slug' => $slug,
                    'content' => $request->content,
                    'excerpt' => $request->excerpt,
                    'status' => $request->status,
                    'is_home' => $request->has('is_home'),
                    'is_featured' => $request->has('is_featured'),
                    'order' => $request->order ?? 0,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'published_at' => $request->status === 'published' ? now() : null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Page::class,
                    'subject_id' => $page->id,
                    'action' => 'created_page',
                    'description' => "Created page: {$page->title}",
                    'properties' => [
                        'page_title' => $page->title,
                        'page_slug' => $page->slug,
                        'status' => $page->status,
                        'is_home' => $page->is_home,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.pages.index')
                    ->with('success', "Page '{$page->title}' has been created successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating page: ' . $e->getMessage());
            return back()->with('error', 'Failed to create page. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified page.
     */
    public function show(Page $page)
    {
        try {
            $this->authorizeTenant($page);
            
            $page->load(['website', 'template', 'sections.components', 'creator', 'updater']);

            // Get page statistics
            $stats = [
                'sections_count' => $page->sections()->count(),
                'components_count' => $page->sections()->withCount('components')->get()->sum('components_count'),
                'children_count' => $page->children()->count(),
                'created_at' => $page->created_at->format('M d, Y'),
                'updated_at' => $page->updated_at->diffForHumans(),
            ];

            return view('admin.pages.show', compact('page', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error showing page: ' . $e->getMessage());
            return back()->with('error', 'Unable to display page details.');
        }
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        try {
            $this->authorizeTenant($page);
            
            $tenantId = auth()->user()->tenant_id;
            $websites = Website::forTenant($tenantId)->get();
            $templates = Template::forTenant($tenantId)->where('is_active', true)->get();
            $parentPages = Page::forTenant($tenantId)
                ->parents()
                ->where('id', '!=', $page->id)
                ->get();

            return view('admin.pages.form', compact('page', 'websites', 'templates', 'parentPages'));
        } catch (\Exception $e) {
            Log::error('Error loading edit page form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit page form.');
        }
    }

    /**
     * Update the specified page.
     */
    public function update(Request $request, Page $page)
    {
        try {
            $this->authorizeTenant($page);

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
                'website_id' => ['required', 'exists:websites,id'],
                'template_id' => ['nullable', 'exists:templates,id'],
                'parent_id' => ['nullable', 'exists:pages,id'],
                'content' => ['nullable', 'string'],
                'excerpt' => ['nullable', 'string', 'max:500'],
                'status' => ['required', 'in:draft,published,archived'],
                'is_home' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
                'order' => ['nullable', 'integer', 'min:0'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:500'],
                'meta_keywords' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // If this is home page, unset other home pages
                if ($request->has('is_home')) {
                    Page::where('website_id', $request->website_id)
                        ->where('id', '!=', $page->id)
                        ->where('is_home', true)
                        ->update(['is_home' => false]);
                }

                $oldData = [
                    'title' => $page->title,
                    'status' => $page->status,
                    'is_home' => $page->is_home,
                    'is_featured' => $page->is_featured,
                ];

                $page->update([
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'website_id' => $request->website_id,
                    'template_id' => $request->template_id,
                    'parent_id' => $request->parent_id,
                    'content' => $request->content,
                    'excerpt' => $request->excerpt,
                    'status' => $request->status,
                    'is_home' => $request->has('is_home'),
                    'is_featured' => $request->has('is_featured'),
                    'order' => $request->order ?? 0,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'published_at' => $request->status === 'published' ? ($page->published_at ?? now()) : null,
                    'updated_by' => auth()->id(),
                ]);

                // Log changes
                $changes = [];
                if ($oldData['title'] !== $request->title) $changes[] = 'title';
                if ($oldData['status'] !== $request->status) $changes[] = 'status';
                if ($oldData['is_home'] !== $request->has('is_home')) $changes[] = 'home_page';
                if ($oldData['is_featured'] !== $request->has('is_featured')) $changes[] = 'featured';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Page::class,
                        'subject_id' => $page->id,
                        'action' => 'updated_page',
                        'description' => "Updated page: {$page->title}",
                        'properties' => [
                            'page_title' => $page->title,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.pages.index')
                    ->with('success', "Page '{$page->title}' has been updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating page: ' . $e->getMessage());
            return back()->with('error', 'Failed to update page. Please try again.')->withInput();
        }
    }

    /**
     * Delete the specified page.
     */
    public function destroy(Page $page)
    {
        try {
            $this->authorizeTenant($page);

            DB::beginTransaction();

            try {
                $pageTitle = $page->title;

                // Check if page has children
                if ($page->children()->exists()) {
                    return back()->with('error', 'Cannot delete page with children. Please delete or move children first.');
                }

                // Delete page sections and components
                foreach ($page->sections as $section) {
                    $section->components()->delete();
                    $section->delete();
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Page::class,
                    'subject_id' => $page->id,
                    'action' => 'deleted_page',
                    'description' => "Deleted page: {$pageTitle}",
                    'properties' => [
                        'page_title' => $pageTitle,
                        'page_slug' => $page->slug,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $page->delete();

                DB::commit();

                return redirect()->route('admin.pages.index')
                    ->with('success', "Page '{$pageTitle}' has been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting page: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete page. Please try again.');
        }
    }

    /**
     * Publish a page.
     */
    public function publish(Request $request, Page $page)
    {
        try {
            $this->authorizeTenant($page);

            $page->publish();

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Page::class,
                'subject_id' => $page->id,
                'action' => 'published_page',
                'description' => "Published page: {$page->title}",
                'properties' => [
                    'page_title' => $page->title,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.pages.index')
                ->with('success', "Page '{$page->title}' has been published.");
        } catch (\Exception $e) {
            Log::error('Error publishing page: ' . $e->getMessage());
            return back()->with('error', 'Failed to publish page.');
        }
    }

    /**
     * Unpublish a page.
     */
    public function unpublish(Request $request, Page $page)
    {
        try {
            $this->authorizeTenant($page);

            $page->unpublish();

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Page::class,
                'subject_id' => $page->id,
                'action' => 'unpublished_page',
                'description' => "Unpublished page: {$page->title}",
                'properties' => [
                    'page_title' => $page->title,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.pages.index')
                ->with('success', "Page '{$page->title}' has been unpublished.");
        } catch (\Exception $e) {
            Log::error('Error unpublishing page: ' . $e->getMessage());
            return back()->with('error', 'Failed to unpublish page.');
        }
    }

    /**
     * Duplicate a page.
     */
    public function duplicate(Request $request, Page $page)
    {
        try {
            $this->authorizeTenant($page);

            DB::beginTransaction();

            try {
                $newPage = $page->duplicate();

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Page::class,
                    'subject_id' => $newPage->id,
                    'action' => 'duplicated_page',
                    'description' => "Duplicated page: {$page->title}",
                    'properties' => [
                        'original_page' => $page->title,
                        'new_page' => $newPage->title,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.pages.edit', $newPage)
                    ->with('success', "Page '{$page->title}' has been duplicated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error duplicating page: ' . $e->getMessage());
            return back()->with('error', 'Failed to duplicate page.');
        }
    }

    /**
     * Export pages to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $pages = Page::forTenant($tenantId)->with(['website'])->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="pages_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($pages) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'ID', 'Title', 'Slug', 'Website', 'Status', 'Home Page', 
                    'Featured', 'Sections', 'Created At', 'Published At'
                ]);

                foreach ($pages as $page) {
                    fputcsv($handle, [
                        $page->id,
                        $page->title,
                        $page->slug,
                        $page->website->name ?? 'N/A',
                        $page->status,
                        $page->is_home ? 'Yes' : 'No',
                        $page->is_featured ? 'Yes' : 'No',
                        $page->sections()->count(),
                        $page->created_at->format('Y-m-d H:i:s'),
                        $page->published_at ? $page->published_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting pages: ' . $e->getMessage());
            return back()->with('error', 'Failed to export pages.');
        }
    }

    /**
     * Authorize that the page belongs to the current tenant.
     */
    protected function authorizeTenant(Page $page)
    {
        if ($page->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}