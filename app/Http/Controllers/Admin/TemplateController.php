<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $query = Template::query();

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%")
                      ->orWhere('category', 'LIKE', "%{$search}%");
                });
            }

            // Category filter
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Type filter (free/premium)
            if ($request->filled('type')) {
                $query->where('is_free', $request->type === 'free' ? 1 : 0);
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            
            $allowedSorts = ['id', 'name', 'category', 'price', 'downloads', 'rating', 'created_at', 'updated_at'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $templates = $query->paginate(12)->withQueryString();

            // Calculate stats
            $stats = [
                'total' => Template::count(),
                'active' => Template::where('is_active', true)->count(),
                'free' => Template::where('is_free', true)->count(),
                'premium' => Template::where('is_free', false)->count(),
            ];

            return view('admin.templates.index', compact('templates', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching templates: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch templates. Please try again.');
        }
    }

    /**
     * Show the form for creating a new template.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            return view('admin.templates.create');
        } catch (\Exception $e) {
            Log::error('Error loading create template form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create template form.');
        }
    }

    /**
     * Store a newly created template.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255', 'unique:templates'],
                'description' => ['nullable', 'string'],
                'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
                'preview_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
                'category' => ['required', 'string', 'max:255'],
                'config' => ['nullable', 'json'],
                'default_data' => ['nullable', 'json'],
                'is_free' => ['required', 'boolean'],
                'price' => ['nullable', 'numeric', 'min:0', 'required_if:is_free,0'],
                'version' => ['nullable', 'string', 'max:50'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Generate slug if not provided
                $slug = $request->slug;
                if (empty($slug)) {
                    $slug = Str::slug($request->name);
                    $count = Template::where('slug', $slug)->count();
                    if ($count > 0) {
                        $slug = $slug . '-' . ($count + 1);
                    }
                }

                // Handle file uploads
                $thumbnailPath = null;
                $previewImagePath = null;

                if ($request->hasFile('thumbnail')) {
                    $thumbnailPath = $request->file('thumbnail')->store('templates/thumbnails', 'public');
                }

                if ($request->hasFile('preview_image')) {
                    $previewImagePath = $request->file('preview_image')->store('templates/previews', 'public');
                }

                // Decode JSON fields
                $config = $request->config ? json_decode($request->config, true) : null;
                $defaultData = $request->default_data ? json_decode($request->default_data, true) : null;

                // Create template
                $template = Template::create([
                    'name' => $request->name,
                    'slug' => $slug,
                    'description' => $request->description,
                    'thumbnail' => $thumbnailPath,
                    'preview_image' => $previewImagePath,
                    'category' => $request->category,
                    'config' => $config,
                    'default_data' => $defaultData,
                    'is_free' => $request->is_free,
                    'price' => $request->is_free ? null : $request->price,
                    'version' => $request->version ?? '1.0.0',
                    'is_active' => $request->has('is_active'),
                ]);

                // Log the activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Template::class,
                    'subject_id' => $template->id,
                    'action' => 'created',
                    'description' => "Created template: {$template->name}",
                    'properties' => [
                        'template_name' => $template->name,
                        'template_slug' => $template->slug,
                        'category' => $template->category,
                        'is_free' => $template->is_free,
                        'price' => $template->price,
                        'is_active' => $template->is_active,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.templates.index')
                    ->with('success', "Template {$template->name} has been created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                // Delete uploaded files if creation fails
                if (isset($thumbnailPath) && Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
                if (isset($previewImagePath) && Storage::disk('public')->exists($previewImagePath)) {
                    Storage::disk('public')->delete($previewImagePath);
                }
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating template: ' . $e->getMessage());
            return back()->with('error', 'Failed to create template. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified template.
     *
     * @param Template $template
     * @return \Illuminate\View\View
     */
    public function show(Template $template)
    {
        try {
            $template->load(['pages', 'activities']);
            
            $stats = [
                'page_count' => $template->pages()->count(),
                'download_count' => $template->downloads ?? 0,
                'rating' => $template->rating ?? 0,
            ];

            return view('admin.templates.show', compact('template', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error showing template: ' . $e->getMessage());
            return back()->with('error', 'Unable to display template details.');
        }
    }

    /**
     * Show the form for editing the specified template.
     *
     * @param Template $template
     * @return \Illuminate\View\View
     */
    public function edit(Template $template)
    {
        try {
            return view('admin.templates.edit', compact('template'));
        } catch (\Exception $e) {
            Log::error('Error loading edit template form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit template form.');
        }
    }

    /**
     * Update the specified template.
     *
     * @param Request $request
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Template $template)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:templates,slug,' . $template->id],
                'description' => ['nullable', 'string'],
                'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
                'preview_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
                'category' => ['required', 'string', 'max:255'],
                'config' => ['nullable', 'json'],
                'default_data' => ['nullable', 'json'],
                'is_free' => ['required', 'boolean'],
                'price' => ['nullable', 'numeric', 'min:0', 'required_if:is_free,0'],
                'version' => ['nullable', 'string', 'max:50'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $oldData = [
                    'name' => $template->name,
                    'slug' => $template->slug,
                    'description' => $template->description,
                    'category' => $template->category,
                    'is_free' => $template->is_free,
                    'price' => $template->price,
                    'version' => $template->version,
                    'is_active' => $template->is_active,
                ];

                // Handle file uploads
                $thumbnailPath = $template->thumbnail;
                $previewImagePath = $template->preview_image;

                if ($request->hasFile('thumbnail')) {
                    // Delete old thumbnail
                    if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                        Storage::disk('public')->delete($template->thumbnail);
                    }
                    $thumbnailPath = $request->file('thumbnail')->store('templates/thumbnails', 'public');
                }

                if ($request->hasFile('preview_image')) {
                    // Delete old preview image
                    if ($template->preview_image && Storage::disk('public')->exists($template->preview_image)) {
                        Storage::disk('public')->delete($template->preview_image);
                    }
                    $previewImagePath = $request->file('preview_image')->store('templates/previews', 'public');
                }

                // Decode JSON fields
                $config = $request->config ? json_decode($request->config, true) : null;
                $defaultData = $request->default_data ? json_decode($request->default_data, true) : null;

                // Update template
                $template->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'description' => $request->description,
                    'thumbnail' => $thumbnailPath,
                    'preview_image' => $previewImagePath,
                    'category' => $request->category,
                    'config' => $config,
                    'default_data' => $defaultData,
                    'is_free' => $request->is_free,
                    'price' => $request->is_free ? null : $request->price,
                    'version' => $request->version ?? '1.0.0',
                    'is_active' => $request->has('is_active'),
                ]);

                // Log changes
                $changes = [];
                foreach ($oldData as $key => $value) {
                    if ($oldData[$key] != $template->$key) {
                        $changes[] = $key;
                    }
                }

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Template::class,
                        'subject_id' => $template->id,
                        'action' => 'updated',
                        'description' => "Updated template: {$template->name}",
                        'properties' => [
                            'template_name' => $template->name,
                            'changes' => $changes,
                            'old_data' => $oldData,
                            'new_data' => [
                                'name' => $template->name,
                                'slug' => $template->slug,
                                'category' => $template->category,
                                'is_free' => $template->is_free,
                                'price' => $template->price,
                                'is_active' => $template->is_active,
                            ],
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.templates.index')
                    ->with('success', "Template {$template->name} has been updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating template: ' . $e->getMessage());
            return back()->with('error', 'Failed to update template. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified template.
     *
     * @param Request $request
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Template $template)
    {
        try {
            // Check if template is being used by any pages
            $pageCount = $template->pages()->count();
            if ($pageCount > 0) {
                return back()->with('error', "Cannot delete template {$template->name} because it is being used by {$pageCount} page(s).");
            }

            DB::beginTransaction();

            try {
                $templateName = $template->name;

                // Delete associated files
                if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                    Storage::disk('public')->delete($template->thumbnail);
                }
                if ($template->preview_image && Storage::disk('public')->exists($template->preview_image)) {
                    Storage::disk('public')->delete($template->preview_image);
                }

                // Log before deletion
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Template::class,
                    'subject_id' => $template->id,
                    'action' => 'deleted',
                    'description' => "Deleted template: {$templateName}",
                    'properties' => [
                        'template_name' => $templateName,
                        'template_slug' => $template->slug,
                        'category' => $template->category,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Delete the template
                $template->delete();

                DB::commit();

                return redirect()->route('admin.templates.index')
                    ->with('success', "Template {$templateName} has been deleted successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting template: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete template. Please try again.');
        }
    }

    /**
     * Preview the specified template.
     *
     * @param Template $template
     * @return \Illuminate\View\View
     */
    public function preview(Template $template)
    {
        try {
            return view('admin.templates.preview', compact('template'));
        } catch (\Exception $e) {
            Log::error('Error previewing template: ' . $e->getMessage());
            return back()->with('error', 'Unable to preview template.');
        }
    }

    /**
     * Toggle template status (activate/deactivate).
     *
     * @param Request $request
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Request $request, Template $template)
    {
        try {
            DB::beginTransaction();

            try {
                $newStatus = !$template->is_active;
                $statusLabel = $newStatus ? 'activated' : 'deactivated';

                $template->update(['is_active' => $newStatus]);

                // Log the activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Template::class,
                    'subject_id' => $template->id,
                    'action' => 'status_changed',
                    'description' => "{$statusLabel} template: {$template->name}",
                    'properties' => [
                        'template_name' => $template->name,
                        'new_status' => $newStatus ? 'active' : 'inactive',
                        'old_status' => $newStatus ? 'inactive' : 'active',
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return back()->with('success', "Template {$template->name} has been {$statusLabel}.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error toggling template status: ' . $e->getMessage());
            return back()->with('error', 'Failed to change template status. Please try again.');
        }
    }

    /**
     * Duplicate a template.
     *
     * @param Request $request
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Request $request, Template $template)
    {
        try {
            DB::beginTransaction();

            try {
                // Generate new slug
                $newSlug = $template->slug . '-copy';
                $count = Template::where('slug', 'LIKE', $newSlug . '%')->count();
                if ($count > 0) {
                    $newSlug = $template->slug . '-copy-' . ($count + 1);
                }

                // Copy files
                $thumbnailPath = null;
                $previewImagePath = null;

                if ($template->thumbnail) {
                    $thumbnailPath = $this->copyFile($template->thumbnail, 'templates/thumbnails');
                }

                if ($template->preview_image) {
                    $previewImagePath = $this->copyFile($template->preview_image, 'templates/previews');
                }

                // Create duplicate template
                $duplicateTemplate = Template::create([
                    'name' => $template->name . ' (Copy)',
                    'slug' => $newSlug,
                    'description' => $template->description,
                    'thumbnail' => $thumbnailPath,
                    'preview_image' => $previewImagePath,
                    'category' => $template->category,
                    'config' => $template->config,
                    'default_data' => $template->default_data,
                    'is_free' => $template->is_free,
                    'price' => $template->price,
                    'version' => $template->version,
                    'is_active' => false, // Set as inactive by default
                ]);

                // Log the activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Template::class,
                    'subject_id' => $duplicateTemplate->id,
                    'action' => 'duplicated',
                    'description' => "Duplicated template: {$template->name}",
                    'properties' => [
                        'original_template' => $template->name,
                        'original_template_id' => $template->id,
                        'duplicate_template' => $duplicateTemplate->name,
                        'duplicate_template_id' => $duplicateTemplate->id,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.templates.edit', $duplicateTemplate)
                    ->with('success', "Template duplicated successfully. Edit the copy to make changes.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error duplicating template: ' . $e->getMessage());
            return back()->with('error', 'Failed to duplicate template. Please try again.');
        }
    }

    /**
     * Copy a file to a new location.
     *
     * @param string $path
     * @param string $destinationDir
     * @return string|null
     */
    protected function copyFile($path, $destinationDir)
    {
        try {
            if (!Storage::disk('public')->exists($path)) {
                return null;
            }

            $filename = pathinfo($path, PATHINFO_FILENAME) . '_copy_' . time() . '.' . pathinfo($path, PATHINFO_EXTENSION);
            $newPath = $destinationDir . '/' . $filename;

            Storage::disk('public')->copy($path, $newPath);

            return $newPath;
        } catch (\Exception $e) {
            Log::error('Error copying file: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Export templates to CSV.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        try {
            $templates = Template::all();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="templates_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($templates) {
                $handle = fopen('php://output', 'w');

                // CSV Headers
                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Slug',
                    'Description',
                    'Category',
                    'Type',
                    'Price',
                    'Version',
                    'Downloads',
                    'Rating',
                    'Status',
                    'Created At',
                    'Updated At',
                ]);

                // Data
                foreach ($templates as $template) {
                    fputcsv($handle, [
                        $template->id,
                        $template->name,
                        $template->slug,
                        $template->description ?? 'N/A',
                        $template->category ?? 'Uncategorized',
                        $template->is_free ? 'Free' : 'Premium',
                        $template->price ?? 'N/A',
                        $template->version ?? '1.0.0',
                        $template->downloads ?? 0,
                        $template->rating ?? 0,
                        $template->is_active ? 'Active' : 'Inactive',
                        $template->created_at->format('Y-m-d H:i:s'),
                        $template->updated_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting templates: ' . $e->getMessage());
            return back()->with('error', 'Failed to export templates.');
        }
    }

    /**
     * Import templates from CSV.
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

            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);

            $requiredHeaders = ['name', 'category', 'is_free'];
            foreach ($requiredHeaders as $required) {
                if (!in_array($required, $header)) {
                    fclose($handle);
                    return back()->with('error', "CSV file missing required column: {$required}");
                }
            }

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    // Generate slug if not provided
                    if (empty($data['slug'])) {
                        $data['slug'] = Str::slug($data['name']);
                        $count = Template::where('slug', $data['slug'])->count();
                        if ($count > 0) {
                            $data['slug'] = $data['slug'] . '-' . ($count + 1);
                        }
                    }

                    // Create template
                    Template::create([
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'description' => $data['description'] ?? null,
                        'category' => $data['category'],
                        'is_free' => strtolower($data['is_free']) === 'free' || $data['is_free'] == '1',
                        'price' => isset($data['price']) ? $data['price'] : null,
                        'version' => $data['version'] ?? '1.0.0',
                        'is_active' => isset($data['status']) ? strtolower($data['status']) === 'active' : true,
                    ]);

                    $imported++;
                }

                fclose($handle);

                // Log the import activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'action' => 'imported_templates',
                    'description' => "Imported {$imported} templates",
                    'properties' => [
                        'imported_count' => $imported,
                        'file_name' => $file->getClientOriginalName(),
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.templates.index')
                    ->with('success', "Successfully imported {$imported} templates.");
            } catch (\Exception $e) {
                DB::rollBack();
                fclose($handle);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error importing templates: ' . $e->getMessage());
            return back()->with('error', 'Failed to import templates: ' . $e->getMessage());
        }
    }

    /**
     * Get template analytics.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analytics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');

            $templates = Template::select('id', 'name', 'category', 'is_free', 'downloads', 'rating')
                ->withCount(['pages' => function ($query) use ($period) {
                    if ($period === 'month') {
                        $query->where('created_at', '>=', now()->subMonth());
                    } elseif ($period === 'quarter') {
                        $query->where('created_at', '>=', now()->subMonths(3));
                    } elseif ($period === 'year') {
                        $query->where('created_at', '>=', now()->subYear());
                    }
                }])
                ->get();

            $data = [
                'templates' => $templates,
                'total_downloads' => $templates->sum('downloads'),
                'average_rating' => $templates->avg('rating'),
                'categories' => $templates->groupBy('category')->map(function ($group) {
                    return $group->count();
                }),
                'free_vs_premium' => [
                    'free' => $templates->where('is_free', true)->count(),
                    'premium' => $templates->where('is_free', false)->count(),
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching template analytics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics.',
            ], 500);
        }
    }

    /**
     * Search for templates (AJAX).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('q', '');

            $templates = Template::where('name', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%")
                ->orWhere('category', 'LIKE', "%{$search}%")
                ->where('is_active', true)
                ->limit(10)
                ->get(['id', 'name', 'slug', 'category', 'is_free', 'price']);

            return response()->json([
                'success' => true,
                'data' => $templates,
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search templates.',
            ], 500);
        }
    }

    /**
     * Get templates by category.
     *
     * @param Request $request
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory(Request $request, $category)
    {
        try {
            $templates = Template::where('category', $category)
                ->where('is_active', true)
                ->get(['id', 'name', 'slug', 'category', 'is_free', 'price', 'thumbnail']);

            return response()->json([
                'success' => true,
                'data' => $templates,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching templates by category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch templates.',
            ], 500);
        }
    }

    /**
     * Get all template categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        try {
            $categories = Template::select('category')
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category');

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching template categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories.',
            ], 500);
        }
    }

    /**
     * Bulk delete templates.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['exists:templates,id'],
            ]);

            $ids = $request->ids;

            DB::beginTransaction();

            try {
                $templates = Template::whereIn('id', $ids)->get();
                $deletedCount = 0;

                foreach ($templates as $template) {
                    // Check if template is being used
                    if ($template->pages()->count() > 0) {
                        continue;
                    }

                    // Delete files
                    if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                        Storage::disk('public')->delete($template->thumbnail);
                    }
                    if ($template->preview_image && Storage::disk('public')->exists($template->preview_image)) {
                        Storage::disk('public')->delete($template->preview_image);
                    }

                    // Log deletion
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Template::class,
                        'subject_id' => $template->id,
                        'action' => 'bulk_deleted',
                        'description' => "Bulk deleted template: {$template->name}",
                        'properties' => [
                            'template_name' => $template->name,
                            'template_id' => $template->id,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    $template->delete();
                    $deletedCount++;
                }

                DB::commit();

                return redirect()->route('admin.templates.index')
                    ->with('success', "Successfully deleted {$deletedCount} template(s).");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error bulk deleting templates: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete templates. Please try again.');
        }
    }

    /**
     * Get template categories list.
     *
     * @return \Illuminate\View\View
     */
    public function categoriesList()
    {
        try {
            $categories = Template::select('category', DB::raw('count(*) as count'))
                ->whereNotNull('category')
                ->groupBy('category')
                ->get();

            return view('admin.templates.categories', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error fetching template categories: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch categories.');
        }
    }
}