<?php
// app/Http/Controllers/Admin/DomainController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Website;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DomainController extends Controller
{
    /**
     * Display a listing of domains.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Domain::forTenant($tenantId)->with(['website']);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('domain', 'LIKE', "%{$search}%");
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Website filter
            if ($request->filled('website_id')) {
                $query->where('website_id', $request->website_id);
            }

            $domains = $query->latest()->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Domain::forTenant($tenantId)->count(),
                'verified' => Domain::forTenant($tenantId)->where('status', 'verified')->count(),
                'pending' => Domain::forTenant($tenantId)->where('status', 'pending')->count(),
                'primary' => Domain::forTenant($tenantId)->where('is_primary', true)->count(),
            ];

            // Get websites for filter
            $websites = Website::forTenant($tenantId)->get();

            return view('admin.domains.index', compact('domains', 'stats', 'websites'));
        } catch (\Exception $e) {
            Log::error('Error fetching domains: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch domains.');
        }
    }

    /**
     * Show the form for creating a new domain.
     */
    public function create()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $websites = Website::forTenant($tenantId)->get();

            return view('admin.domains.form', compact('websites'));
        } catch (\Exception $e) {
            Log::error('Error loading create domain form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create domain form.');
        }
    }

    /**
     * Store a newly created domain.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'domain' => ['required', 'string', 'max:255', 'unique:domains'],
                'website_id' => ['required', 'exists:websites,id'],
                'ssl_enabled' => ['nullable', 'boolean'],
                'ssl_expires_at' => ['nullable', 'date'],
                'status' => ['required', 'in:pending,verified,failed'],
                'is_primary' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                // If this is primary, unset other primary domains for this website
                if ($request->has('is_primary')) {
                    Domain::where('website_id', $request->website_id)
                        ->where('is_primary', true)
                        ->update(['is_primary' => false]);
                }

                $domain = Domain::create([
                    'tenant_id' => $tenantId,
                    'website_id' => $request->website_id,
                    'domain' => $request->domain,
                    'ssl_enabled' => $request->has('ssl_enabled'),
                    'ssl_expires_at' => $request->ssl_expires_at,
                    'status' => $request->status,
                    'is_primary' => $request->has('is_primary'),
                    'created_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Domain::class,
                    'subject_id' => $domain->id,
                    'action' => 'created_domain',
                    'description' => "Added domain: {$domain->domain}",
                    'properties' => [
                        'domain' => $domain->domain,
                        'website_id' => $request->website_id,
                        'status' => $request->status,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.domains.show', $domain)
                    ->with('success', "Domain '{$domain->domain}' added successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating domain: ' . $e->getMessage());
            return back()->with('error', 'Failed to add domain.');
        }
    }

    /**
     * Display the specified domain.
     */
    public function show(Domain $domain)
    {
        try {
            $this->authorizeTenant($domain);
            
            $domain->load(['website']);
            $ipAddress = request()->getClientIp() ?? '192.168.1.1';

            return view('admin.domains.show', compact('domain', 'ipAddress'));
        } catch (\Exception $e) {
            Log::error('Error showing domain: ' . $e->getMessage());
            return back()->with('error', 'Unable to display domain.');
        }
    }

    /**
     * Show the form for editing the specified domain.
     */
    public function edit(Domain $domain)
    {
        try {
            $this->authorizeTenant($domain);
            
            $tenantId = auth()->user()->tenant_id;
            $websites = Website::forTenant($tenantId)->get();

            return view('admin.domains.form', compact('domain', 'websites'));
        } catch (\Exception $e) {
            Log::error('Error loading edit domain form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit domain form.');
        }
    }

    /**
     * Update the specified domain.
     */
    public function update(Request $request, Domain $domain)
    {
        try {
            $this->authorizeTenant($domain);

            $validator = Validator::make($request->all(), [
                'domain' => ['required', 'string', 'max:255', 'unique:domains,domain,' . $domain->id],
                'website_id' => ['required', 'exists:websites,id'],
                'ssl_enabled' => ['nullable', 'boolean'],
                'ssl_expires_at' => ['nullable', 'date'],
                'status' => ['required', 'in:pending,verified,failed'],
                'is_primary' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // If this is primary, unset other primary domains for this website
                if ($request->has('is_primary')) {
                    Domain::where('website_id', $request->website_id)
                        ->where('id', '!=', $domain->id)
                        ->where('is_primary', true)
                        ->update(['is_primary' => false]);
                }

                $oldStatus = $domain->status;

                $domain->update([
                    'domain' => $request->domain,
                    'website_id' => $request->website_id,
                    'ssl_enabled' => $request->has('ssl_enabled'),
                    'ssl_expires_at' => $request->ssl_expires_at,
                    'status' => $request->status,
                    'is_primary' => $request->has('is_primary'),
                    'updated_by' => auth()->id(),
                ]);

                // Log activity
                $changes = [];
                if ($oldStatus !== $request->status) {
                    $changes[] = "Status changed from {$oldStatus} to {$request->status}";
                }

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Domain::class,
                        'subject_id' => $domain->id,
                        'action' => 'updated_domain',
                        'description' => "Updated domain: {$domain->domain}",
                        'properties' => [
                            'domain' => $domain->domain,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.domains.show', $domain)
                    ->with('success', "Domain '{$domain->domain}' updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating domain: ' . $e->getMessage());
            return back()->with('error', 'Failed to update domain.');
        }
    }

    /**
     * Delete the specified domain.
     */
    public function destroy(Domain $domain)
    {
        try {
            $this->authorizeTenant($domain);

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
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $domain->delete();

                DB::commit();

                return redirect()->route('admin.domains.index')
                    ->with('success', "Domain '{$domainName}' deleted successfully.");
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
     * Verify a domain.
     */
    public function verify(Request $request, Domain $domain)
    {
        try {
            $this->authorizeTenant($domain);

            DB::beginTransaction();

            try {
                $domain->update([
                    'status' => 'verified',
                    'verified_at' => now(),
                    'updated_by' => auth()->id(),
                ]);

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

                DB::commit();

                return redirect()->route('admin.domains.show', $domain)
                    ->with('success', "Domain '{$domain->domain}' verified successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error verifying domain: ' . $e->getMessage());
            return back()->with('error', 'Failed to verify domain.');
        }
    }

    /**
     * Set domain as primary.
     */
    public function setPrimary(Request $request, Domain $domain)
    {
        try {
            $this->authorizeTenant($domain);

            DB::beginTransaction();

            try {
                // Unset other primary domains for this website
                Domain::where('website_id', $domain->website_id)
                    ->where('id', '!=', $domain->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);

                $domain->update([
                    'is_primary' => true,
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Domain::class,
                    'subject_id' => $domain->id,
                    'action' => 'set_primary_domain',
                    'description' => "Set '{$domain->domain}' as primary domain",
                    'properties' => [
                        'domain' => $domain->domain,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.domains.show', $domain)
                    ->with('success', "'{$domain->domain}' set as primary domain.");
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
     * Check domain availability.
     */
    public function checkAvailability(Request $request)
    {
        try {
            $request->validate([
                'domain' => ['required', 'string'],
            ]);

            $exists = Domain::where('domain', $request->domain)->exists();

            return response()->json([
                'success' => true,
                'available' => !$exists,
                'message' => $exists ? 'Domain already taken' : 'Domain available',
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking domain availability: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check domain availability.',
            ], 500);
        }
    }

    /**
     * Export domains to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $domains = Domain::forTenant($tenantId)->with(['website'])->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="domains_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($domains) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'Domain', 'Website', 'Status', 'Primary', 'SSL', 'Verified At', 'Created At'
                ]);

                foreach ($domains as $domain) {
                    fputcsv($handle, [
                        $domain->domain,
                        $domain->website->name ?? 'N/A',
                        $domain->status,
                        $domain->is_primary ? 'Yes' : 'No',
                        $domain->ssl_enabled ? 'Enabled' : 'Disabled',
                        $domain->verified_at ? $domain->verified_at->format('Y-m-d H:i:s') : 'N/A',
                        $domain->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting domains: ' . $e->getMessage());
            return back()->with('error', 'Failed to export domains.');
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