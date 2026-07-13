<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Category::forTenant($tenantId)->with(['parent', 'children']);

            // Search filter
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Featured filter
            if ($request->filled('featured')) {
                $query->where('is_featured', $request->featured === 'true' ? 1 : 0);
            }

            // Parent filter
            if ($request->filled('parent_id')) {
                if ($request->parent_id === 'null') {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $request->parent_id);
                }
            }

            // Sort
            $sortField = $request->get('sort', 'sort_order');
            $sortDirection = $request->get('direction', 'asc');
            $allowedSorts = ['id', 'name', 'sort_order', 'created_at', 'is_active'];
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $categories = $query->paginate(15)->withQueryString();

            // Get all parent categories for filter
            $parents = Category::forTenant($tenantId)->parents()->ordered()->get();

            // Get statistics
            $stats = [
                'total' => Category::forTenant($tenantId)->count(),
                'active' => Category::forTenant($tenantId)->where('is_active', true)->count(),
                'inactive' => Category::forTenant($tenantId)->where('is_active', false)->count(),
                'featured' => Category::forTenant($tenantId)->where('is_featured', true)->count(),
                'parents' => Category::forTenant($tenantId)->parents()->count(),
                'children' => Category::forTenant($tenantId)->children()->count(),
            ];

            // Get notification variables for top-nav
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.blog.categories.index', compact(
                'categories',
                'parents',
                'stats',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch categories. Please try again.');
        }
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $parents = Category::forTenant($tenantId)->parents()->ordered()->get();

            return view('admin.blog.categories.create', compact('parents'));
        } catch (\Exception $e) {
            Log::error('Error loading create category form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create category form.');
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255', 'unique:categories'],
                'parent_id' => ['nullable', 'exists:categories,id'],
                'description' => ['nullable', 'string'],
                'icon' => ['nullable', 'string', 'max:255'],
                'color' => ['nullable', 'string', 'max:50'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string'],
                'meta_keywords' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
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
                    $slug = Str::slug($request->name);
                    $count = Category::where('slug', $slug)->count();
                    if ($count > 0) {
                        $slug = $slug . '-' . ($count + 1);
                    }
                }

                // Handle image upload
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('categories', 'public');
                }

                $category = Category::create([
                    'tenant_id' => $tenantId,
                    'name' => $request->name,
                    'slug' => $slug,
                    'parent_id' => $request->parent_id,
                    'description' => $request->description,
                    'icon' => $request->icon,
                    'color' => $request->color ?? 'gray',
                    'image' => $imagePath,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'is_active' => $request->has('is_active'),
                    'is_featured' => $request->has('is_featured'),
                    'sort_order' => $request->sort_order ?? 0,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Category::class,
                    'subject_id' => $category->id,
                    'action' => 'created_category',
                    'description' => "Created category: {$category->name}",
                    'properties' => [
                        'category_name' => $category->name,
                        'category_slug' => $category->slug,
                        'parent_id' => $category->parent_id,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.blog.categories.index')
                    ->with('success', "Category '{$category->name}' has been created successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return back()->with('error', 'Failed to create category. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        try {
            $this->authorizeTenant($category);
            
            $category->load(['parent', 'children', 'creator', 'updater']);

            // Get category statistics
            $stats = [
                'total_posts' => $category->posts()->count(),
                'published_posts' => $category->posts()->where('status', 'published')->count(),
                'draft_posts' => $category->posts()->where('status', 'draft')->count(),
                'children_count' => $category->children()->count(),
            ];

            // Get recent posts in this category
            $recentPosts = $category->posts()->with('user')->latest()->limit(10)->get();

            return view('admin.blog.categories.show', compact('category', 'stats', 'recentPosts'));
        } catch (\Exception $e) {
            Log::error('Error showing category: ' . $e->getMessage());
            return back()->with('error', 'Unable to display category details.');
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        try {
            $this->authorizeTenant($category);
            
            $tenantId = auth()->user()->tenant_id;
            $parents = Category::forTenant($tenantId)
                ->parents()
                ->where('id', '!=', $category->id)
                ->ordered()
                ->get();

            return view('admin.blog.categories.edit', compact('category', 'parents'));
        } catch (\Exception $e) {
            Log::error('Error loading edit category form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit category form.');
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $this->authorizeTenant($category);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
                'parent_id' => ['nullable', 'exists:categories,id'],
                'description' => ['nullable', 'string'],
                'icon' => ['nullable', 'string', 'max:255'],
                'color' => ['nullable', 'string', 'max:50'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string'],
                'meta_keywords' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Handle image upload
                $imagePath = $category->image;
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($category->image && Storage::disk('public')->exists($category->image)) {
                        Storage::disk('public')->delete($category->image);
                    }
                    $imagePath = $request->file('image')->store('categories', 'public');
                }

                $oldData = [
                    'name' => $category->name,
                    'parent_id' => $category->parent_id,
                    'is_active' => $category->is_active,
                    'is_featured' => $category->is_featured,
                ];

                $category->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'parent_id' => $request->parent_id,
                    'description' => $request->description,
                    'icon' => $request->icon,
                    'color' => $request->color ?? 'gray',
                    'image' => $imagePath,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'is_active' => $request->has('is_active'),
                    'is_featured' => $request->has('is_featured'),
                    'sort_order' => $request->sort_order ?? 0,
                    'updated_by' => auth()->id(),
                ]);

                // Log changes
                $changes = [];
                if ($oldData['name'] !== $request->name) $changes[] = 'name';
                if ($oldData['parent_id'] != $request->parent_id) $changes[] = 'parent';
                if ($oldData['is_active'] != $request->has('is_active')) $changes[] = 'status';
                if ($oldData['is_featured'] != $request->has('is_featured')) $changes[] = 'featured';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Category::class,
                        'subject_id' => $category->id,
                        'action' => 'updated_category',
                        'description' => "Updated category: {$category->name}",
                        'properties' => [
                            'category_name' => $category->name,
                            'category_slug' => $category->slug,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.blog.categories.index')
                    ->with('success', "Category '{$category->name}' has been updated successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return back()->with('error', 'Failed to update category. Please try again.')->withInput();
        }
    }

    /**
     * Delete the specified category.
     */
    public function destroy(Category $category)
    {
        try {
            $this->authorizeTenant($category);

            DB::beginTransaction();

            try {
                // Check if category has children
                if ($category->children()->exists()) {
                    return back()->with('error', 'Cannot delete category with children. Please delete or move children first.');
                }

                // Check if category has posts
                if ($category->posts()->exists()) {
                    return back()->with('error', 'Cannot delete category with associated posts. Please remove posts first.');
                }

                $categoryName = $category->name;

                // Delete image
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Category::class,
                    'subject_id' => $category->id,
                    'action' => 'deleted_category',
                    'description' => "Deleted category: {$categoryName}",
                    'properties' => [
                        'category_name' => $categoryName,
                        'category_slug' => $category->slug,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $category->delete();

                DB::commit();

                return redirect()->route('admin.blog.categories.index')
                    ->with('success', "Category '{$categoryName}' has been deleted successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete category. Please try again.');
        }
    }

    /**
     * Toggle category status.
     */
    public function toggle(Request $request, Category $category)
    {
        try {
            $this->authorizeTenant($category);

            $newStatus = !$category->is_active;
            $statusLabel = $newStatus ? 'activated' : 'deactivated';

            $category->update(['is_active' => $newStatus]);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Category::class,
                'subject_id' => $category->id,
                'action' => 'toggled_category',
                'description' => "{$statusLabel} category: {$category->name}",
                'properties' => [
                    'category_name' => $category->name,
                    'new_status' => $newStatus ? 'active' : 'inactive',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Category '{$category->name}' has been {$statusLabel}.",
                    'status' => $newStatus,
                ]);
            }

            return back()->with('success', "Category '{$category->name}' has been {$statusLabel}.");
        } catch (\Exception $e) {
            Log::error('Error toggling category: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to toggle category status.',
                ], 500);
            }
            
            return back()->with('error', 'Failed to toggle category status.');
        }
    }

    /**
     * Reorder categories.
     */
    public function reorder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order' => ['required', 'array'],
                'order.*' => ['required', 'exists:categories,id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order data provided.',
                ], 422);
            }

            DB::beginTransaction();

            try {
                foreach ($request->order as $index => $categoryId) {
                    Category::where('id', $categoryId)
                        ->update(['sort_order' => $index]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Categories reordered successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error reordering categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder categories.',
            ], 500);
        }
    }

    /**
     * Get category tree data.
     */
    public function tree(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $categories = Category::forTenant($tenantId)
                ->with(['children' => function ($query) {
                    $query->ordered();
                }])
                ->parents()
                ->ordered()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $this->buildTree($categories),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching category tree: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category tree.',
            ], 500);
        }
    }

    /**
     * Search categories (AJAX).
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $tenantId = auth()->user()->tenant_id;

            $categories = Category::forTenant($tenantId)
                ->search($search)
                ->active()
                ->limit(10)
                ->get(['id', 'name', 'slug', 'icon', 'color']);

            return response()->json([
                'success' => true,
                'data' => $categories,
                'results' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'icon' => $category->icon ?? 'fa-folder',
                        'color' => $category->color ?? 'gray',
                        'text' => $category->name,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search categories.',
            ], 500);
        }
    }

    /**
     * Delete category image.
     */
    public function deleteImage(Request $request, Category $category)
    {
        try {
            $this->authorizeTenant($category);

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
                $category->update(['image' => null]);

                return response()->json([
                    'success' => true,
                    'message' => 'Category image deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image found to delete.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting category image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category image.',
            ], 500);
        }
    }

    /**
     * Export categories.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $categories = Category::forTenant($tenantId)->with('parent')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="categories_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($categories) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'ID', 'Name', 'Slug', 'Parent', 'Description', 'Icon', 
                    'Color', 'Status', 'Featured', 'Sort Order', 'Created At'
                ]);

                foreach ($categories as $category) {
                    fputcsv($handle, [
                        $category->id,
                        $category->name,
                        $category->slug,
                        $category->parent->name ?? 'N/A',
                        $category->description ?? 'N/A',
                        $category->icon ?? 'N/A',
                        $category->color,
                        $category->is_active ? 'Active' : 'Inactive',
                        $category->is_featured ? 'Yes' : 'No',
                        $category->sort_order,
                        $category->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting categories: ' . $e->getMessage());
            return back()->with('error', 'Failed to export categories.');
        }
    }

    // ============================================
    // API METHODS (for frontend AJAX)
    // ============================================

    /**
     * API endpoint to fetch categories for frontend.
     */
    public function apiIndex(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Category::forTenant($tenantId)
                ->with(['parent', 'children'])
                ->ordered();

            // Filter by status
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Filter by parent
            if ($request->filled('parent_id')) {
                if ($request->parent_id === 'null') {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $request->parent_id);
                }
            }

            // Search
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Limit results
            if ($request->filled('limit')) {
                $query->limit((int) $request->limit);
            }

            $categories = $query->get();

            // Format for frontend
            $formattedCategories = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => $category->icon ?? 'fa-folder',
                    'color' => $category->color ?? 'gray',
                    'color_hex' => $category->color_hex,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                    'parent_id' => $category->parent_id,
                    'parent_name' => $category->parent->name ?? null,
                    'is_active' => (bool) $category->is_active,
                    'is_featured' => (bool) $category->is_featured,
                    'sort_order' => $category->sort_order,
                    'post_count' => $category->post_count,
                    'children_count' => $category->children_count,
                    'level' => $category->level,
                    'full_path' => $category->full_path,
                    'created_at' => $category->created_at->toISOString(),
                    'formatted_created_at' => $category->formatted_created_at,
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'icon' => $child->icon ?? 'fa-folder',
                            'color' => $child->color ?? 'gray',
                            'is_active' => (bool) $child->is_active,
                            'post_count' => $child->post_count,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedCategories,
                'total' => $categories->count(),
                'message' => 'Categories fetched successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching categories API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch categories. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * API endpoint to fetch category tree.
     */
    public function apiTree(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $categories = Category::forTenant($tenantId)
                ->with(['children' => function ($query) {
                    $query->ordered();
                }])
                ->parents()
                ->ordered()
                ->get();

            $tree = $this->buildApiTree($categories);

            return response()->json([
                'success' => true,
                'data' => $tree,
                'message' => 'Category tree fetched successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching category tree API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch category tree. Please try again.'
            ], 500);
        }
    }

    /**
     * API endpoint to fetch a single category.
     */
    public function apiShow($id)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $category = Category::forTenant($tenantId)
                ->with(['parent', 'children'])
                ->findOrFail($id);

            $formattedCategory = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'icon' => $category->icon ?? 'fa-folder',
                'color' => $category->color ?? 'gray',
                'color_hex' => $category->color_hex,
                'image' => $category->image ? asset('storage/' . $category->image) : null,
                'parent_id' => $category->parent_id,
                'parent_name' => $category->parent->name ?? null,
                'is_active' => (bool) $category->is_active,
                'is_featured' => (bool) $category->is_featured,
                'sort_order' => $category->sort_order,
                'post_count' => $category->post_count,
                'children_count' => $category->children_count,
                'level' => $category->level,
                'full_path' => $category->full_path,
                'meta_title' => $category->meta_title,
                'meta_description' => $category->meta_description,
                'meta_keywords' => $category->meta_keywords,
                'created_at' => $category->created_at->toISOString(),
                'formatted_created_at' => $category->formatted_created_at,
                'updated_at' => $category->updated_at->toISOString(),
            ];

            return response()->json([
                'success' => true,
                'data' => $formattedCategory,
                'message' => 'Category fetched successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching category API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * API endpoint to search categories.
     */
    public function apiSearch(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $search = $request->get('q', '');

            $categories = Category::forTenant($tenantId)
                ->search($search)
                ->active()
                ->limit(10)
                ->get(['id', 'name', 'slug', 'icon', 'color']);

            $formatted = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon ?? 'fa-folder',
                    'color' => $category->color ?? 'gray',
                    'text' => $category->name,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'results' => $formatted,
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching categories API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search categories'
            ], 500);
        }
    }

    // ============================================
    // PRIVATE HELPER METHODS
    // ============================================

    /**
     * Build tree structure for categories.
     */
    protected function buildTree($categories)
    {
        $tree = [];

        foreach ($categories as $category) {
            $node = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon,
                'color' => $category->color,
                'is_active' => $category->is_active,
                'children' => [],
            ];

            if ($category->children->isNotEmpty()) {
                $node['children'] = $this->buildTree($category->children);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Build tree structure for API response.
     */
    protected function buildApiTree($categories)
    {
        $tree = [];

        foreach ($categories as $category) {
            $node = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon ?? 'fa-folder',
                'color' => $category->color ?? 'gray',
                'color_hex' => $category->color_hex,
                'is_active' => (bool) $category->is_active,
                'post_count' => $category->post_count,
                'children' => [],
            ];

            if ($category->children->isNotEmpty()) {
                $node['children'] = $this->buildApiTree($category->children);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Authorize that the category belongs to the current tenant.
     */
    protected function authorizeTenant(Category $category)
    {
        if ($category->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}