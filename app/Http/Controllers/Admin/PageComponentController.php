<?php
// app/Http/Controllers/Admin/PageComponentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageComponent;
use App\Models\PageSection;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageComponentController extends Controller
{
    /**
     * Store a newly created component.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'section_id' => ['required', 'exists:page_sections,id'],
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
                $section = PageSection::findOrFail($request->section_id);
                $this->authorizeSection($section);

                // Get max order
                $maxOrder = $section->components()->max('order') ?? 0;

                $component = PageComponent::create([
                    'section_id' => $section->id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'content' => $request->content ? json_decode($request->content, true) : null,
                    'settings' => $request->settings ? json_decode($request->settings, true) : null,
                    'order' => $maxOrder + 1,
                    'is_active' => $request->has('is_active'),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => PageComponent::class,
                    'subject_id' => $component->id,
                    'action' => 'created_component',
                    'description' => "Created component '{$component->name}' in section: {$section->name}",
                    'properties' => [
                        'component_name' => $component->name,
                        'component_type' => $component->type,
                        'section_name' => $section->name,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->back()
                    ->with('success', "Component '{$component->name}' has been created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating component: ' . $e->getMessage());
            return back()->with('error', 'Failed to create component. Please try again.');
        }
    }

    /**
     * Get component data for editing.
     */
    public function edit(PageComponent $component)
    {
        try {
            $this->authorizeComponent($component);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $component->id,
                    'section_id' => $component->section_id,
                    'name' => $component->name,
                    'type' => $component->type,
                    'content' => $component->content,
                    'settings' => $component->settings,
                    'is_active' => $component->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching component data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch component data.',
            ], 500);
        }
    }

    /**
     * Update the specified component.
     */
    public function update(Request $request, PageComponent $component)
    {
        try {
            $this->authorizeComponent($component);

            $validator = Validator::make($request->all(), [
                'section_id' => ['required', 'exists:page_sections,id'],
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
                    'name' => $component->name,
                    'type' => $component->type,
                    'is_active' => $component->is_active,
                ];

                $component->update([
                    'section_id' => $request->section_id,
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
                        'subject_type' => PageComponent::class,
                        'subject_id' => $component->id,
                        'action' => 'updated_component',
                        'description' => "Updated component '{$component->name}'",
                        'properties' => [
                            'component_name' => $component->name,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->back()
                    ->with('success', "Component '{$component->name}' has been updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating component: ' . $e->getMessage());
            return back()->with('error', 'Failed to update component. Please try again.');
        }
    }

    /**
     * Delete the specified component.
     */
    public function destroy(PageComponent $component)
    {
        try {
            $this->authorizeComponent($component);

            DB::beginTransaction();

            try {
                $componentName = $component->name;
                $sectionName = $component->section->name;

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => PageComponent::class,
                    'subject_id' => $component->id,
                    'action' => 'deleted_component',
                    'description' => "Deleted component '{$componentName}' from section: {$sectionName}",
                    'properties' => [
                        'component_name' => $componentName,
                        'section_name' => $sectionName,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $component->delete();

                DB::commit();

                return redirect()->back()
                    ->with('success', "Component '{$componentName}' has been deleted successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting component: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete component.');
        }
    }

    /**
     * Reorder components.
     */
    public function reorder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order' => ['required', 'array'],
                'order.*' => ['required', 'exists:page_components,id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order data.',
                ], 422);
            }

            DB::beginTransaction();

            try {
                foreach ($request->order as $index => $componentId) {
                    PageComponent::where('id', $componentId)
                        ->update(['order' => $index]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Components reordered successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error reordering components: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder components.',
            ], 500);
        }
    }

    /**
     * Authorize that the component belongs to the current tenant.
     */
    protected function authorizeComponent(PageComponent $component)
    {
        if ($component->section->page->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}