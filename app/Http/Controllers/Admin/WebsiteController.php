<?php
// app/Http/Controllers/Admin/WebsiteController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\User;
use App\Models\Template;
use App\Models\Domain;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    /**
     * Display a listing of websites.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Website::forTenant($tenantId)
                ->with(['user', 'template']);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('domain', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $allowedSorts = ['id', 'name', 'created_at', 'views', 'status'];
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $websites = $query->paginate(12)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Website::forTenant($tenantId)->count(),
                'published' => Website::forTenant($tenantId)->where('status', 'published')->count(),
                'draft' => Website::forTenant($tenantId)->where('status', 'draft')->count(),
                'archived' => Website::forTenant($tenantId)->where('status', 'archived')->count(),
                'total_views' => Website::forTenant($tenantId)->sum('views'),
            ];

            // Get notification variables for top-nav
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.websites.index', compact(
                'websites',
                'stats',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching websites: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch websites. Please try again.');
        }
    }

    /**
     * Show the form for creating a new website.
     */
    public function create()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $users = User::forTenant($tenantId)->get();
            $templates = Template::forTenant($tenantId)->where('is_active', true)->get();

            return view('admin.websites.create', compact('users', 'templates'));
        } catch (\Exception $e) {
            Log::error('Error loading create website form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create website form.');
        }
    }

    /**
     * Store a newly created website.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'user_id' => ['required', 'exists:users,id'],
                'domain' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'template_id' => ['required', 'exists:templates,id'],
                'status' => ['required', 'in:draft,published,archived'],
                'is_featured' => ['nullable', 'boolean'],
                'has_ssl' => ['nullable', 'boolean'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:500'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                // Generate unique slug
                $slug = Str::slug($request->name);
                $count = Website::where('slug', $slug)->count();
                if ($count > 0) {
                    $slug = $slug . '-' . ($count + 1);
                }

                $website = Website::create([
                    'tenant_id' => $tenantId,
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'slug' => $slug,
                    'domain' => $request->domain,
                    'description' => $request->description,
                    'template_id' => $request->template_id,
                    'status' => $request->status,
                    'is_featured' => $request->has('is_featured'),
                    'has_ssl' => $request->has('has_ssl'),
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Website::class,
                    'subject_id' => $website->id,
                    'action' => 'created_website',
                    'description' => "Created website: {$website->name}",
                    'properties' => [
                        'website_name' => $website->name,
                        'website_slug' => $website->slug,
                        'status' => $website->status,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.websites.index')
                    ->with('success', "Website '{$website->name}' has been created successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating website: ' . $e->getMessage());
            return back()->with('error', 'Failed to create website. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified website.
     */
    public function show(Website $website)
    {
        try {
            $this->authorizeTenant($website);
            
            $website->load(['user', 'template', 'pages', 'domains']);

            // Get website statistics
            $stats = [
                'views' => $website->views ?? 0,
                'pages_count' => $website->pages()->count(),
                'domains_count' => $website->domains()->count(),
                'created_at' => $website->created_at->format('M d, Y'),
                'owner_name' => $website->user->name ?? 'Unknown',
            ];

            return view('admin.websites.show', compact('website', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error showing website: ' . $e->getMessage());
            return back()->with('error', 'Unable to display website details.');
        }
    }

    /**
     * Show the form for editing the specified website.
     */
    public function edit(Website $website)
    {
        try {
            $this->authorizeTenant($website);
            
            $tenantId = auth()->user()->tenant_id;
            $users = User::forTenant($tenantId)->get();
            $templates = Template::forTenant($tenantId)->where('is_active', true)->get();

            return view('admin.websites.edit', compact('website', 'users', 'templates'));
        } catch (\Exception $e) {
            Log::error('Error loading edit website form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit website form.');
        }
    }

    /**
     * Update the specified website.
     */
    public function update(Request $request, Website $website)
    {
        try {
            $this->authorizeTenant($website);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'domain' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['required', 'in:draft,published,archived'],
                'is_featured' => ['nullable', 'boolean'],
                'has_ssl' => ['nullable', 'boolean'],
                'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Handle screenshot upload
                $screenshotPath = $website->screenshot;
                if ($request->hasFile('screenshot')) {
                    // Delete old screenshot
                    if ($website->screenshot && Storage::disk('public')->exists($website->screenshot)) {
                        Storage::disk('public')->delete($website->screenshot);
                    }
                    $screenshotPath = $request->file('screenshot')->store('websites/screenshots', 'public');
                }

                $oldData = [
                    'name' => $website->name,
                    'status' => $website->status,
                    'is_featured' => $website->is_featured,
                ];

                $website->update([
                    'name' => $request->name,
                    'domain' => $request->domain,
                    'description' => $request->description,
                    'status' => $request->status,
                    'is_featured' => $request->has('is_featured'),
                    'has_ssl' => $request->has('has_ssl'),
                    'screenshot' => $screenshotPath,
                    'updated_by' => auth()->id(),
                ]);

                // Log changes
                $changes = [];
                if ($oldData['name'] !== $request->name) $changes[] = 'name';
                if ($oldData['status'] !== $request->status) $changes[] = 'status';
                if ($oldData['is_featured'] !== $request->has('is_featured')) $changes[] = 'featured';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Website::class,
                        'subject_id' => $website->id,
                        'action' => 'updated_website',
                        'description' => "Updated website: {$website->name}",
                        'properties' => [
                            'website_name' => $website->name,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.websites.index')
                    ->with('success', "Website '{$website->name}' has been updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating website: ' . $e->getMessage());
            return back()->with('error', 'Failed to update website. Please try again.')->withInput();
        }
    }

    /**
     * Delete the specified website.
     */
    public function destroy(Website $website)
    {
        try {
            $this->authorizeTenant($website);

            DB::beginTransaction();

            try {
                $websiteName = $website->name;

                // Delete screenshot
                if ($website->screenshot && Storage::disk('public')->exists($website->screenshot)) {
                    Storage::disk('public')->delete($website->screenshot);
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Website::class,
                    'subject_id' => $website->id,
                    'action' => 'deleted_website',
                    'description' => "Deleted website: {$websiteName}",
                    'properties' => [
                        'website_name' => $websiteName,
                        'website_slug' => $website->slug,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $website->delete();

                DB::commit();

                return redirect()->route('admin.websites.index')
                    ->with('success', "Website '{$websiteName}' has been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting website: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete website. Please try again.');
        }
    }

    /**
     * Show publish confirmation page.
     */
    public function publish(Website $website)
    {
        try {
            $this->authorizeTenant($website);
            
            if ($website->status === 'published') {
                return redirect()->route('admin.websites.show', $website)
                    ->with('info', 'This website is already published.');
            }

            return view('admin.websites.publish', compact('website'));
        } catch (\Exception $e) {
            Log::error('Error loading publish page: ' . $e->getMessage());
            return back()->with('error', 'Unable to load publish page.');
        }
    }

    /**
     * Publish the website.
     */
    public function publishStore(Request $request, Website $website)
    {
        try {
            $this->authorizeTenant($website);

            DB::beginTransaction();

            try {
                $website->update([
                    'status' => 'published',
                    'published_at' => now(),
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Website::class,
                    'subject_id' => $website->id,
                    'action' => 'published_website',
                    'description' => "Published website: {$website->name}",
                    'properties' => [
                        'website_name' => $website->name,
                        'website_slug' => $website->slug,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.websites.show', $website)
                    ->with('success', "Website '{$website->name}' has been published successfully!");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error publishing website: ' . $e->getMessage());
            return back()->with('error', 'Failed to publish website. Please try again.');
        }
    }

    /**
     * Manage domains for a website.
     */
    public function domains(Website $website)
    {
        try {
            $this->authorizeTenant($website);
            
            $domains = $website->domains()->get();
            $ipAddress = request()->getClientIp() ?? '192.168.1.1';

            return view('admin.websites.domains', compact('website', 'domains', 'ipAddress'));
        } catch (\Exception $e) {
            Log::error('Error loading domains page: ' . $e->getMessage());
            return back()->with('error', 'Unable to load domains page.');
        }
    }

    /**
     * Add a domain to the website.
     */
    public function domainStore(Request $request, Website $website)
    {
        try {
            $this->authorizeTenant($website);

            $validator = Validator::make($request->all(), [
                'domain' => ['required', 'string', 'max:255', 'unique:domains,domain'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $domain = Domain::create([
                    'website_id' => $website->id,
                    'domain' => $request->domain,
                    'is_verified' => false,
                    'is_primary' => $website->domains()->count() === 0,
                    'tenant_id' => auth()->user()->tenant_id,
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Domain::class,
                    'subject_id' => $domain->id,
                    'action' => 'added_domain',
                    'description' => "Added domain '{$domain->domain}' to website: {$website->name}",
                    'properties' => [
                        'domain' => $domain->domain,
                        'website_name' => $website->name,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.websites.domains', $website)
                    ->with('success', "Domain '{$domain->domain}' has been added successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error adding domain: ' . $e->getMessage());
            return back()->with('error', 'Failed to add domain. Please try again.');
        }
    }

    /**
     * Verify a domain.
     */
    public function domainVerify(Request $request, Domain $domain)
    {
        try {
            // In production, you would verify DNS records here
            $domain->update(['is_verified' => true]);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Domain::class,
                'subject_id' => $domain->id,
                'action' => 'verified_domain',
                'description' => "Verified domain: {$domain->domain}",
                'properties' => [
                    'domain' => $domain->domain,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Domain verified successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying domain: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify domain.',
            ], 500);
        }
    }

    /**
     * Set a domain as primary.
     */
    public function domainPrimary(Request $request, Website $website, Domain $domain)
    {
        try {
            $this->authorizeTenant($website);

            DB::beginTransaction();

            try {
                // Reset all domains to non-primary
                $website->domains()->update(['is_primary' => false]);
                
                // Set this domain as primary
                $domain->update(['is_primary' => true]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Domain::class,
                    'subject_id' => $domain->id,
                    'action' => 'set_primary_domain',
                    'description' => "Set '{$domain->domain}' as primary domain",
                    'properties' => [
                        'domain' => $domain->domain,
                        'website_name' => $website->name,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.websites.domains', $website)
                    ->with('success', "Domain '{$domain->domain}' is now the primary domain.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error setting primary domain: ' . $e->getMessage());
            return back()->with('error', 'Failed to set primary domain.');
        }
    }

    /**
     * Delete a domain.
     */
    public function domainDestroy(Request $request, Website $website, Domain $domain)
    {
        try {
            $this->authorizeTenant($website);

            if ($domain->is_primary && $website->domains()->count() > 1) {
                return back()->with('error', 'Cannot delete the primary domain. Set another domain as primary first.');
            }

            DB::beginTransaction();

            try {
                $domainName = $domain->domain;

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Domain::class,
                    'subject_id' => $domain->id,
                    'action' => 'deleted_domain',
                    'description' => "Deleted domain: {$domainName}",
                    'properties' => [
                        'domain' => $domainName,
                        'website_name' => $website->name,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $domain->delete();

                DB::commit();

                return redirect()->route('admin.websites.domains', $website)
                    ->with('success', "Domain '{$domainName}' has been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting domain: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete domain.');
        }
    }

    /**
     * Show website analytics.
     */
    public function analytics(Website $website)
    {
        try {
            $this->authorizeTenant($website);
            return view('admin.websites.analytics', compact('website'));
        } catch (\Exception $e) {
            Log::error('Error loading analytics page: ' . $e->getMessage());
            return back()->with('error', 'Unable to load analytics page.');
        }
    }

    /**
     * Delete website screenshot.
     */
    public function screenshotDestroy(Request $request, Website $website)
    {
        try {
            $this->authorizeTenant($website);

            if ($website->screenshot && Storage::disk('public')->exists($website->screenshot)) {
                Storage::disk('public')->delete($website->screenshot);
                $website->update(['screenshot' => null]);

                return response()->json([
                    'success' => true,
                    'message' => 'Screenshot deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No screenshot found to delete.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting screenshot: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete screenshot.',
            ], 500);
        }
    }

    /**
     * Export websites to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $websites = Website::forTenant($tenantId)->with('user')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="websites_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($websites) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'ID', 'Name', 'Slug', 'Domain', 'Owner', 'Status', 
                    'Views', 'Featured', 'SSL', 'Created At'
                ]);

                foreach ($websites as $website) {
                    fputcsv($handle, [
                        $website->id,
                        $website->name,
                        $website->slug,
                        $website->domain ?? 'N/A',
                        $website->user->name ?? 'Unknown',
                        $website->status,
                        $website->views ?? 0,
                        $website->is_featured ? 'Yes' : 'No',
                        $website->has_ssl ? 'Yes' : 'No',
                        $website->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting websites: ' . $e->getMessage());
            return back()->with('error', 'Failed to export websites.');
        }
    }

    /**
     * Show SEO settings page.
     */
    public function seo(Website $website)
    {
        try {
            $this->authorizeTenant($website);
            return view('admin.websites.seo', compact('website'));
        } catch (\Exception $e) {
            Log::error('Error loading SEO page: ' . $e->getMessage());
            return back()->with('error', 'Unable to load SEO page.');
        }
    }

    /**
     * Update SEO settings.
     */
    public function updateSeo(Request $request, Website $website)
    {
        try {
            $this->authorizeTenant($website);

            $validator = Validator::make($request->all(), [
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:500'],
                'meta_keywords' => ['nullable', 'string'],
                'og_title' => ['nullable', 'string', 'max:255'],
                'og_description' => ['nullable', 'string', 'max:500'],
                'og_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $ogImagePath = $website->og_image;
                if ($request->hasFile('og_image')) {
                    if ($website->og_image && Storage::disk('public')->exists($website->og_image)) {
                        Storage::disk('public')->delete($website->og_image);
                    }
                    $ogImagePath = $request->file('og_image')->store('websites/og_images', 'public');
                }

                $website->update([
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'og_title' => $request->og_title,
                    'og_description' => $request->og_description,
                    'og_image' => $ogImagePath,
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Website::class,
                    'subject_id' => $website->id,
                    'action' => 'updated_seo',
                    'description' => "Updated SEO settings for website: {$website->name}",
                    'properties' => [
                        'website_name' => $website->name,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.websites.seo', $website)
                    ->with('success', "SEO settings for '{$website->name}' have been updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating SEO: ' . $e->getMessage());
            return back()->with('error', 'Failed to update SEO settings. Please try again.');
        }
    }

    /**
     * Authorize that the website belongs to the current tenant.
     */
    protected function authorizeTenant(Website $website)
    {
        if ($website->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Get analytics data via API.
     */
    public function getAnalyticsData(Request $request, Website $website)
    {
        try {
            $this->authorizeTenant($website);
            
            $period = $request->get('period', 'week');
            $dates = $this->getDateRange($period);

            // Get analytics data (in production, this would come from your analytics system)
            $stats = [
                'total_visitors' => rand(100, 1000),
                'unique_visitors' => rand(50, 500),
                'page_views' => rand(200, 2000),
                'avg_time' => rand(1, 10) . 'm',
            ];

            $charts = [
                'trend' => [
                    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    'data' => [rand(10, 50), rand(20, 60), rand(30, 70), rand(40, 80), rand(50, 90), rand(30, 70), rand(20, 60)],
                ],
                'sources' => [
                    'labels' => ['Direct', 'Google', 'Social Media', 'Email', 'Referral'],
                    'data' => [30, 25, 20, 15, 10],
                ],
            ];

            $topPages = [
                ['title' => 'Home', 'views' => rand(100, 500), 'unique_visitors' => rand(50, 200), 'avg_time' => '2m 30s'],
                ['title' => 'About', 'views' => rand(50, 200), 'unique_visitors' => rand(30, 100), 'avg_time' => '1m 45s'],
                ['title' => 'Services', 'views' => rand(80, 300), 'unique_visitors' => rand(40, 150), 'avg_time' => '3m 15s'],
                ['title' => 'Blog', 'views' => rand(60, 250), 'unique_visitors' => rand(35, 120), 'avg_time' => '4m 20s'],
                ['title' => 'Contact', 'views' => rand(40, 150), 'unique_visitors' => rand(20, 80), 'avg_time' => '1m 10s'],
            ];

            return response()->json([
                'success' => true,
                'data' => compact('stats', 'charts', 'topPages'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching analytics data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics data.',
            ], 500);
        }
    }

    /**
     * Get date range for analytics.
     */
    private function getDateRange($period)
    {
        $start = now();
        $end = now();

        switch ($period) {
            case 'today':
                $start = now()->startOfDay();
                break;
            case 'week':
                $start = now()->startOfWeek();
                break;
            case 'month':
                $start = now()->startOfMonth();
                break;
            case 'quarter':
                $start = now()->startOfQuarter();
                break;
            case 'year':
                $start = now()->startOfYear();
                break;
            default:
                $start = now()->startOfWeek();
        }

        return [$start, $end];
    }
}