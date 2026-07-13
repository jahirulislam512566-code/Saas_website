<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    /**
     * Display a listing of plans.
     */
 public function index(Request $request)
{
    try {
        $query = Plan::withCount('subscriptions');

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting
        $sortField = $request->get('sort', 'sort_order');
        $sortDirection = in_array($request->get('direction'), ['asc', 'desc']) 
            ? $request->get('direction') 
            : 'asc';

        $allowedSorts = ['id', 'name', 'price_monthly', 'price_yearly', 'sort_order', 'created_at'];
        
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        $plans = $query->paginate(12)->withQueryString();

        // Stats - Fixed column names
        $stats = [
            'total'           => Plan::count(),
            'active'          => Plan::where('is_active', true)->count(),
            'subscribers'     => Subscription::whereIn('status', ['active', 'trialing'])->count(),
            'monthly_revenue' => Subscription::where('status', 'active')
                                    ->where('billing_cycle', 'monthly')
                                    ->sum('price'), // ← Changed from 'amount' to 'price'
        ];

        return view('admin.plans.index', compact('plans', 'stats'));
    } catch (\Exception $e) {
        Log::error('Plans index error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Unable to load plans. Please try again.');
    }
}
    /**
     * Show create plan form.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a new plan.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                    => ['required', 'string', 'max:255'],
            'slug'                    => ['nullable', 'string', 'max:255', 'unique:plans'],
            'description'             => ['nullable', 'string'],
            'price_monthly'           => ['required', 'numeric', 'min:0'],
            'price_yearly'            => ['nullable', 'numeric', 'min:0'],
            'currency'                => ['required', 'string', 'size:3'],
            'stripe_price_id_monthly' => ['nullable', 'string', 'max:255'],
            'stripe_price_id_yearly'  => ['nullable', 'string', 'max:255'],
            'features'                => ['nullable', 'array'],
            'features.*'              => ['nullable', 'string', 'max:255'],
            'limits'                  => ['nullable', 'array'],
            'limits.*'                => ['nullable', 'string', 'max:255'],
            'trial_days'              => ['required', 'integer', 'min:0', 'max:365'],
            'is_active'               => ['boolean'],
            'is_featured'             => ['boolean'],
            'sort_order'              => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Plan::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $features = array_values(array_filter($request->features ?? [], fn($f) => !empty(trim($f))));
            $limits   = array_values(array_filter($request->limits ?? [], fn($l) => !empty(trim($l))));

            $plan = Plan::create([
                'name'                    => $request->name,
                'slug'                    => $slug,
                'description'             => $request->description,
                'price_monthly'           => $request->price_monthly,
                'price_yearly'            => $request->price_yearly,
                'currency'                => strtoupper($request->currency),
                'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
                'stripe_price_id_yearly'  => $request->stripe_price_id_yearly,
                'features'                => $features,
                'limits'                  => $limits,
                'trial_days'              => $request->trial_days ?? 14,
                'is_active'               => $request->boolean('is_active', true),
                'is_featured'             => $request->boolean('is_featured', false),
                'sort_order'              => $request->sort_order ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.plans.index')
                ->with('success', "Plan '{$plan->name}' created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Plan creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create plan.')->withInput();
        }
    }

    /**
     * Show plan details.
     */
    public function show(Plan $plan)
    {
        $plan->load(['subscriptions' => fn($q) => $q->with('user')->latest()->limit(10)]);

        $stats = [
            'total_subscribers'    => $plan->subscriptions()->count(),
            'active_subscribers'   => $plan->subscriptions()->where('status', 'active')->count(),
            'trialing_subscribers' => $plan->subscriptions()->where('status', 'trialing')->count(),
            'canceled_subscribers' => $plan->subscriptions()->where('status', 'canceled')->count(),
            'monthly_revenue'      => $plan->subscriptions()
                ->where('status', 'active')
                ->where('billing_cycle', 'monthly')
                ->sum('amount'),
        ];

        return view('admin.plans.show', compact('plan', 'stats'));
    }

    /**
     * Show edit form.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update plan.
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'name'                    => ['required', 'string', 'max:255'],
            'slug'                    => ['required', 'string', 'max:255', 'unique:plans,slug,' . $plan->id],
            'description'             => ['nullable', 'string'],
            'price_monthly'           => ['required', 'numeric', 'min:0'],
            'price_yearly'            => ['nullable', 'numeric', 'min:0'],
            'currency'                => ['required', 'string', 'size:3'],
            'stripe_price_id_monthly' => ['nullable', 'string'],
            'stripe_price_id_yearly'  => ['nullable', 'string'],
            'features'                => ['nullable', 'array'],
            'features.*'              => ['nullable', 'string', 'max:255'],
            'limits'                  => ['nullable', 'array'],
            'limits.*'                => ['nullable', 'string', 'max:255'],
            'trial_days'              => ['required', 'integer', 'min:0', 'max:365'],
            'is_active'               => ['boolean'],
            'is_featured'             => ['boolean'],
            'sort_order'              => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $features = array_values(array_filter($request->features ?? [], fn($f) => !empty(trim($f))));
            $limits   = array_values(array_filter($request->limits ?? [], fn($l) => !empty(trim($l))));

            $plan->update([
                'name'                    => $request->name,
                'slug'                    => $request->slug,
                'description'             => $request->description,
                'price_monthly'           => $request->price_monthly,
                'price_yearly'            => $request->price_yearly,
                'currency'                => strtoupper($request->currency),
                'stripe_price_id_monthly' => $request->stripe_price_id_monthly,
                'stripe_price_id_yearly'  => $request->stripe_price_id_yearly,
                'features'                => $features,
                'limits'                  => $limits,
                'trial_days'              => $request->trial_days,
                'is_active'               => $request->boolean('is_active'),
                'is_featured'             => $request->boolean('is_featured'),
                'sort_order'              => $request->sort_order ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.plans.index')
                ->with('success', "Plan '{$plan->name}' updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Plan update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update plan.')->withInput();
        }
    }

    /**
     * Delete a plan.
     */
    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->whereIn('status', ['active', 'trialing'])->exists()) {
            return back()->with('error', "Cannot delete '{$plan->name}' because it has active subscribers.");
        }

        $planName = $plan->name;
        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('success', "Plan '{$planName}' deleted successfully.");
    }

    /**
     * Toggle plan active status.
     */
    public function toggle(Plan $plan)
    {
        $newStatus = !$plan->is_active;
        $plan->update(['is_active' => $newStatus]);

        $status = $newStatus ? 'activated' : 'deactivated';

        return back()->with('success', "Plan '{$plan->name}' has been {$status}.");
    }

    /**
     * Duplicate a plan.
     */
    public function duplicate(Plan $plan)
    {
        $newSlug = $plan->slug . '-copy';
        $counter = 1;
        while (Plan::where('slug', $newSlug)->exists()) {
            $newSlug = $plan->slug . '-copy-' . $counter++;
        }

        $duplicate = Plan::create([
            'name'         => $plan->name . ' (Copy)',
            'slug'         => $newSlug,
            'description'  => $plan->description,
            'price_monthly'=> $plan->price_monthly,
            'price_yearly' => $plan->price_yearly,
            'currency'     => $plan->currency,
            'features'     => $plan->features,
            'limits'       => $plan->limits,
            'trial_days'   => $plan->trial_days,
            'is_active'    => false,
            'is_featured'  => $plan->is_featured,
            'sort_order'   => $plan->sort_order + 10,
        ]);

        return redirect()
            ->route('admin.plans.edit', $duplicate)
            ->with('success', 'Plan duplicated successfully. You can now customize it.');
    }

     public function export(Request $request)
    {
        try {
            $plans = Plan::withCount('subscriptions')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="plans_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($plans) {
                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'ID', 'Name', 'Slug', 'Description', 'Monthly Price',
                    'Yearly Price', 'Currency', 'Trial Days', 'Features',
                    'Limits', 'Status', 'Featured', 'Sort Order',
                    'Total Subscribers', 'Created At', 'Updated At'
                ]);

                foreach ($plans as $plan) {
                    fputcsv($handle, [
                        $plan->id,
                        $plan->name,
                        $plan->slug,
                        $plan->description ?? 'N/A',
                        $plan->price_monthly,
                        $plan->price_yearly ?? 'N/A',
                        $plan->currency,
                        $plan->trial_days,
                        $plan->features ? implode('; ', $plan->features) : 'N/A',
                        $plan->limits ? implode('; ', $plan->limits) : 'N/A',
                        $plan->is_active ? 'Active' : 'Inactive',
                        $plan->is_featured ? 'Yes' : 'No',
                        $plan->sort_order,
                        $plan->subscriptions_count,
                        $plan->created_at->format('Y-m-d H:i:s'),
                        $plan->updated_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting plans: ' . $e->getMessage());
            return back()->with('error', 'Failed to export plans.');
        }
    }

    /**
     * Show plan import form.
     *
     * @return \Illuminate\View\View
     */
    public function importForm()
    {
        return view('admin.plans.import');
    }

    /**
     * Import plans from CSV.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
            ]);

            // Read CSV file
            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);

            // Validate header
            $requiredHeaders = ['name', 'price_monthly', 'currency'];
            foreach ($requiredHeaders as $required) {
                if (!in_array($required, $header)) {
                    fclose($handle);
                    return back()->with('error', "CSV file missing required column: {$required}");
                }
            }

            $imported = 0;
            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    // Generate slug if not provided
                    if (empty($data['slug'])) {
                        $data['slug'] = Str::slug($data['name']);
                        $count = Plan::where('slug', $data['slug'])->count();
                        if ($count > 0) {
                            $data['slug'] = $data['slug'] . '-' . ($count + 1);
                        }
                    }

                    // Parse features and limits
                    $features = !empty($data['features']) ? explode(';', $data['features']) : [];
                    $limits = !empty($data['limits']) ? explode(';', $data['limits']) : [];

                    $features = array_filter(array_map('trim', $features));
                    $limits = array_filter(array_map('trim', $limits));

                    Plan::create([
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'description' => $data['description'] ?? null,
                        'price_monthly' => $data['price_monthly'],
                        'price_yearly' => $data['price_yearly'] ?? null,
                        'currency' => $data['currency'],
                        'stripe_price_id_monthly' => null,
                        'stripe_price_id_yearly' => null,
                        'features' => array_values($features),
                        'limits' => array_values($limits),
                        'trial_days' => $data['trial_days'] ?? 14,
                        'is_active' => ($data['status'] ?? 'active') === 'active',
                        'is_featured' => ($data['featured'] ?? 'no') === 'yes',
                        'sort_order' => $data['sort_order'] ?? 0,
                    ]);

                    $imported++;
                }

                fclose($handle);
                DB::commit();

                return redirect()->route('admin.plans.index')
                    ->with('success', "Successfully imported {$imported} plans.");
            } catch (\Exception $e) {
                DB::rollBack();
                fclose($handle);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error importing plans: ' . $e->getMessage());
            return back()->with('error', 'Failed to import plans: ' . $e->getMessage());
        }
    }
}