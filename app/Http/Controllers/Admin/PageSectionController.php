<?php
// app/Http/Controllers/Admin/PageSectionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageSectionController extends Controller
{
    /**
     * Display page sections.
     */
    public function index(Page $page)
    {
        try {
            $this->authorizeTenant($page);
            
            $sections = $page->sections()->with('components')->ordered()->get();

            return view('admin.pages.sections.index', compact('page', 'sections'));
        } catch (\Exception $e) {
            Log::error('Error loading page sections: ' . $e->getMessage());
            return back()->with('error', 'Unable to load page sections.');
        }
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request, Page $page)
    {
        try {
            $this->authorizeTenant($page);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'content' => ['nullable', 'json'],
                'settings' => ['nullable', 'json'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Get max order
                $maxOrder = $page->sections()->max('order') ?? 0;

                $section = PageSection::create([
                    'page_id' => $page->id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'content' => $request->content ? json_decode($request->content, true) : null,
                    'settings' => $request->settings ? json_decode($request->settings, true) : null,
                    'order' => $maxOrder + 1,
                    'is_active' => $request->has('is_active'),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => PageSection::class,
                    'subject_id' => $section->id,
                    'action' => 'created_section',
                    'description' => "Created section '{$section->name}' in page: {$page->title}",
                    'properties' => [
                        'section_name' => $section->name,
                        'section_type' => $section->type,
                        'page_title' => $page->title,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.pages.sections', $page)
                    ->with('success', "Section '{$section->name}' has been created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating section: ' . $e->getMessage());
            return back()->with('error', 'Failed to create section. Please try again.');
        }
    }

    /**
     * Get section data for editing.
     */
    public function edit(PageSection $section)
    {
        try {
            $this->authorizeSection($section);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $section->id,
                    'name' => $section->name,
                    'type' => $section->type,
                    'content' => $section->content,
                    'settings' => $section->settings,
                    'is_active' => $section->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching section data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch section data.',
            ], 500);
        }
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, PageSection $section)
    {
        try {
            $this->authorizeSection($section);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'content' => ['nullable', 'json'],
                'settings' => ['nullable', 'json'],
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
                    'name' => $section->name,
                    'type' => $section->type,
                    'is_active' => $section->is_active,
                ];

                $section->update([
                    'name' => $request->name,
                    'type' => $request->type,
                    'content' => $request->content ? json_decode($request->content, true) : null,
                    'settings' => $request->settings ? json_decode($request->settings, true) : null,
                    'is_active' => $request->has('is_active'),
                ]);

                // Log changes
                $changes = [];
                if ($oldData['name'] !== $request->name) $changes[] = 'name';
                if ($oldData['type'] !== $request->type) $changes[] = 'type';
                if ($oldData['is_active'] !== $request->has('is_active')) $changes[] = 'status';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => PageSection::class,
                        'subject_id' => $section->id,
                        'action' => 'updated_section',
                        'description' => "Updated section '{$section->name}'",
                        'properties' => [
                            'section_name' => $section->name,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->back()
                    ->with('success', "Section '{$section->name}' has been updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating section: ' . $e->getMessage());
            return back()->with('error', 'Failed to update section. Please try again.');
        }
    }

    /**
     * Delete the specified section.
     */
    public function destroy(PageSection $section)
    {
        try {
            $this->authorizeSection($section);

            DB::beginTransaction();

            try {
                $sectionName = $section->name;
                $pageTitle = $section->page->title;

                // Delete components
                $section->components()->delete();

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => PageSection::class,
                    'subject_id' => $section->id,
                    'action' => 'deleted_section',
                    'description' => "Deleted section '{$sectionName}' from page: {$pageTitle}",
                    'properties' => [
                        'section_name' => $sectionName,
                        'page_title' => $pageTitle,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $section->delete();

                DB::commit();

                return redirect()->back()
                    ->with('success', "Section '{$sectionName}' has been deleted successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting section: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete section.');
        }
    }

    /**
     * Reorder sections.
     */
    public function reorder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order' => ['required', 'array'],
                'order.*' => ['required', 'exists:page_sections,id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order data.',
                ], 422);
            }

            DB::beginTransaction();

            try {
                foreach ($request->order as $index => $sectionId) {
                    PageSection::where('id', $sectionId)
                        ->update(['order' => $index]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Sections reordered successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error reordering sections: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder sections.',
            ], 500);
        }
    }

    /**
     * Authorize that the section belongs to the current tenant.
     */
    protected function authorizeSection(PageSection $section)
    {
        if ($section->page->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}