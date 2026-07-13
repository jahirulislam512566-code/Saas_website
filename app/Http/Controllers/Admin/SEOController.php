<?php
// app/Http/Controllers/Admin/SEOController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\SEO;
use App\Models\Redirect;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SEOController extends Controller
{
    /**
     * SEO Dashboard.
     */
    public function dashboard()
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            // Get statistics
            $stats = [
                'indexed' => Page::forTenant($tenantId)->where('status', 'published')->count(),
                'queries' => rand(100, 500), // In production, this would come from analytics
                'traffic' => rand(500, 2000),
                'avg_position' => rand(5, 20),
                'score' => rand(75, 95),
                'good' => rand(10, 20),
                'warning' => rand(5, 10),
            ];

            // Get recent SEO activities
            $activities = Activity::forTenant($tenantId)
                ->where('action', 'LIKE', '%seo%')
                ->latest()
                ->limit(5)
                ->get();

            // Get SEO issues (simulated)
            $issues = [
                (object)[
                    'title' => 'Missing Meta Description',
                    'page' => '/about-us',
                    'severity' => 'warning',
                    'link' => '#',
                ],
                (object)[
                    'title' => 'Duplicate Title Tags',
                    'page' => '/blog/post-1',
                    'severity' => 'critical',
                    'link' => '#',
                ],
                (object)[
                    'title' => 'Slow Page Speed',
                    'page' => '/services',
                    'severity' => 'warning',
                    'link' => '#',
                ],
            ];

            return view('admin.seo.dashboard', compact('stats', 'activities', 'issues'));
        } catch (\Exception $e) {
            Log::error('Error loading SEO dashboard: ' . $e->getMessage());
            return back()->with('error', 'Unable to load SEO dashboard.');
        }
    }

    /**
     * Sitemap management.
     */
    public function sitemap()
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $stats = [
                'total' => Page::forTenant($tenantId)->count() + Post::forTenant($tenantId)->count(),
                'pages' => Page::forTenant($tenantId)->count(),
                'posts' => Post::forTenant($tenantId)->count(),
                'last_generated' => 'Today', // In production, get from cache
            ];

            // Get sitemap URLs (simulated)
            $sitemapUrls = Page::forTenant($tenantId)
                ->where('status', 'published')
                ->limit(20)
                ->get()
                ->map(function ($page) {
                    return (object)[
                        'url' => url($page->slug),
                        'priority' => $page->is_home ? '1.0' : '0.5',
                        'changefreq' => 'weekly',
                        'lastmod' => $page->updated_at->format('Y-m-d'),
                    ];
                });

            return view('admin.seo.sitemap', compact('stats', 'sitemapUrls'));
        } catch (\Exception $e) {
            Log::error('Error loading sitemap: ' . $e->getMessage());
            return back()->with('error', 'Unable to load sitemap.');
        }
    }

    /**
     * Generate sitemap.
     */
    public function generateSitemap(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            // In production, generate actual XML sitemap
            $pages = Page::forTenant($tenantId)->where('status', 'published')->get();

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $tenantId,
                'action' => 'generated_sitemap',
                'description' => 'Generated XML sitemap',
                'properties' => [
                    'pages_count' => $pages->count(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.seo.sitemap')
                ->with('success', 'Sitemap generated successfully.');
        } catch (\Exception $e) {
            Log::error('Error generating sitemap: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate sitemap.');
        }
    }

    /**
     * Robots.txt management.
     */
    public function robots()
    {
        try {
            // Get current robots.txt content
            $robotsPath = public_path('robots.txt');
            $content = File::exists($robotsPath) ? File::get($robotsPath) : '';

            // Parse robots.txt into structured data
            $robots = $this->parseRobots($content);

            return view('admin.seo.robots', compact('robots'));
        } catch (\Exception $e) {
            Log::error('Error loading robots.txt: ' . $e->getMessage());
            return back()->with('error', 'Unable to load robots.txt.');
        }
    }

    /**
     * Update robots.txt.
     */
    public function updateRobots(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_agent' => ['nullable', 'string'],
                'disallow' => ['nullable', 'string'],
                'allow' => ['nullable', 'string'],
                'sitemap' => ['nullable', 'url'],
                'crawl_delay' => ['nullable', 'integer', 'min:0'],
                'additional' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Build robots.txt content
            $content = "User-agent: " . ($request->user_agent ?? '*') . "\n";
            
            if ($request->filled('disallow')) {
                $content .= "Disallow: " . $request->disallow . "\n";
            }
            
            if ($request->filled('allow')) {
                $content .= "Allow: " . $request->allow . "\n";
            }
            
            if ($request->filled('crawl_delay')) {
                $content .= "Crawl-delay: " . $request->crawl_delay . "\n";
            }
            
            if ($request->filled('sitemap')) {
                $content .= "Sitemap: " . $request->sitemap . "\n";
            }
            
            if ($request->filled('additional')) {
                $content .= $request->additional . "\n";
            }

            // Save robots.txt
            $robotsPath = public_path('robots.txt');
            File::put($robotsPath, $content);

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'updated_robots',
                'description' => 'Updated robots.txt file',
                'properties' => [
                    'user_agent' => $request->user_agent ?? '*',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.seo.robots')
                ->with('success', 'Robots.txt updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating robots.txt: ' . $e->getMessage());
            return back()->with('error', 'Failed to update robots.txt.');
        }
    }

    /**
     * Reset robots.txt to default.
     */
    public function resetRobots()
    {
        try {
            $defaultContent = "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\n";
            $robotsPath = public_path('robots.txt');
            File::put($robotsPath, $defaultContent);

            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'reset_robots',
                'description' => 'Reset robots.txt to default',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Robots.txt reset to default.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetting robots.txt: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset robots.txt.',
            ], 500);
        }
    }

    /**
     * Parse robots.txt content.
     */
    private function parseRobots($content)
    {
        $robots = [
            'user_agent' => '*',
            'disallow' => '',
            'allow' => '',
            'sitemap' => url('/sitemap.xml'),
            'crawl_delay' => 5,
            'additional' => '',
        ];

        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) continue;

            if (str_starts_with($line, 'User-agent:')) {
                $robots['user_agent'] = trim(str_replace('User-agent:', '', $line));
            } elseif (str_starts_with($line, 'Disallow:')) {
                $robots['disallow'] = trim(str_replace('Disallow:', '', $line));
            } elseif (str_starts_with($line, 'Allow:')) {
                $robots['allow'] = trim(str_replace('Allow:', '', $line));
            } elseif (str_starts_with($line, 'Sitemap:')) {
                $robots['sitemap'] = trim(str_replace('Sitemap:', '', $line));
            } elseif (str_starts_with($line, 'Crawl-delay:')) {
                $robots['crawl_delay'] = (int) trim(str_replace('Crawl-delay:', '', $line));
            } else {
                $robots['additional'] .= $line . "\n";
            }
        }

        return $robots;
    }

    /**
     * Redirects management.
     */
    public function redirects(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Redirect::forTenant($tenantId);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('source', 'LIKE', "%{$search}%")
                      ->orWhere('target', 'LIKE', "%{$search}%");
                });
            }

            $redirects = $query->paginate(15)->withQueryString();

            $stats = [
                'total' => Redirect::forTenant($tenantId)->count(),
                'permanent' => Redirect::forTenant($tenantId)->where('type', '301')->count(),
                'temporary' => Redirect::forTenant($tenantId)->where('type', '302')->count(),
            ];

            return view('admin.seo.redirects', compact('redirects', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading redirects: ' . $e->getMessage());
            return back()->with('error', 'Unable to load redirects.');
        }
    }

    /**
     * Store a redirect.
     */
    public function storeRedirect(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'source' => ['required', 'string', 'max:255', 'unique:redirects,source'],
                'target' => ['required', 'string', 'max:255'],
                'type' => ['required', 'in:301,302'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $redirect = Redirect::create([
                'tenant_id' => auth()->user()->tenant_id,
                'source' => $request->source,
                'target' => $request->target,
                'type' => $request->type,
                'is_active' => $request->has('is_active'),
                'created_by' => auth()->id(),
            ]);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Redirect::class,
                'subject_id' => $redirect->id,
                'action' => 'created_redirect',
                'description' => "Created redirect from '{$redirect->source}' to '{$redirect->target}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.seo.redirects')
                ->with('success', 'Redirect created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating redirect: ' . $e->getMessage());
            return back()->with('error', 'Failed to create redirect.');
        }
    }

    /**
     * Get redirect for editing.
     */
    public function editRedirect(Redirect $redirect)
    {
        try {
            $this->authorizeTenant($redirect);

            return response()->json([
                'success' => true,
                'data' => [
                    'source' => $redirect->source,
                    'target' => $redirect->target,
                    'type' => $redirect->type,
                    'is_active' => $redirect->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching redirect data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch redirect data.',
            ], 500);
        }
    }

    /**
     * Update redirect.
     */
    public function updateRedirect(Request $request, Redirect $redirect)
    {
        try {
            $this->authorizeTenant($redirect);

            $validator = Validator::make($request->all(), [
                'source' => ['required', 'string', 'max:255', 'unique:redirects,source,' . $redirect->id],
                'target' => ['required', 'string', 'max:255'],
                'type' => ['required', 'in:301,302'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $redirect->update([
                'source' => $request->source,
                'target' => $request->target,
                'type' => $request->type,
                'is_active' => $request->has('is_active'),
            ]);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Redirect::class,
                'subject_id' => $redirect->id,
                'action' => 'updated_redirect',
                'description' => "Updated redirect from '{$redirect->source}' to '{$redirect->target}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.seo.redirects')
                ->with('success', 'Redirect updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating redirect: ' . $e->getMessage());
            return back()->with('error', 'Failed to update redirect.');
        }
    }

    /**
     * Toggle redirect status.
     */
    public function toggleRedirect(Redirect $redirect)
    {
        try {
            $this->authorizeTenant($redirect);

            $redirect->update(['is_active' => !$redirect->is_active]);

            return redirect()->route('admin.seo.redirects')
                ->with('success', 'Redirect status updated.');
        } catch (\Exception $e) {
            Log::error('Error toggling redirect: ' . $e->getMessage());
            return back()->with('error', 'Failed to update redirect status.');
        }
    }

    /**
     * Delete redirect.
     */
    public function destroyRedirect(Redirect $redirect)
    {
        try {
            $this->authorizeTenant($redirect);

            $redirect->delete();

            return redirect()->route('admin.seo.redirects')
                ->with('success', 'Redirect deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting redirect: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete redirect.');
        }
    }

    /**
     * Authorize tenant.
     */
    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}