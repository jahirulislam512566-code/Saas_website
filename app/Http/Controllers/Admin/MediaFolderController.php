<?php
// app/Http/Controllers/Admin/MediaFolderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFolder;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MediaFolderController extends Controller
{
    /**
     * Display folders.
     */
    public function index()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $folders = MediaFolder::forTenant($tenantId)->withCount('media')->get();
            
            $stats = [
                'total' => $folders->count(),
                'files' => MediaFolder::forTenant($tenantId)->withCount('media')->get()->sum('media_count'),
                'storage' => $this->formatSize(MediaFolder::forTenant($tenantId)->with('media')->get()->sum(function($folder) {
                    return $folder->media->sum('size');
                })),
            ];

            $rootFiles = MediaFolder::forTenant($tenantId)->whereNull('folder_id')->withCount('media')->get()->sum('media_count');

            return view('admin.media.folders', compact('folders', 'stats', 'rootFiles'));
        } catch (\Exception $e) {
            Log::error('Error fetching folders: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch folders.');
        }
    }

    /**
     * Store a new folder.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'parent_id' => ['nullable', 'exists:media_folders,id'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                $folder = MediaFolder::create([
                    'tenant_id' => $tenantId,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'description' => $request->description,
                    'parent_id' => $request->parent_id,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => MediaFolder::class,
                    'subject_id' => $folder->id,
                    'action' => 'created_media_folder',
                    'description' => "Created media folder: {$folder->name}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.media.folders')
                    ->with('success', "Folder '{$folder->name}' has been created.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating folder: ' . $e->getMessage());
            return back()->with('error', 'Failed to create folder.');
        }
    }

    /**
     * Get folder data for editing.
     */
    public function edit(MediaFolder $folder)
    {
        try {
            $this->authorizeTenant($folder);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'parent_id' => $folder->parent_id,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching folder data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch folder data.',
            ], 500);
        }
    }

    /**
     * Update folder.
     */
    public function update(Request $request, MediaFolder $folder)
    {
        try {
            $this->authorizeTenant($folder);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'parent_id' => ['nullable', 'exists:media_folders,id'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $oldName = $folder->name;
                
                $folder->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'description' => $request->description,
                    'parent_id' => $request->parent_id,
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => MediaFolder::class,
                    'subject_id' => $folder->id,
                    'action' => 'updated_media_folder',
                    'description' => "Updated media folder: {$folder->name}",
                    'properties' => [
                        'old_name' => $oldName,
                        'new_name' => $folder->name,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.media.folders')
                    ->with('success', "Folder '{$folder->name}' has been updated.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating folder: ' . $e->getMessage());
            return back()->with('error', 'Failed to update folder.');
        }
    }

    /**
     * Delete folder.
     */
    public function destroy(MediaFolder $folder)
    {
        try {
            $this->authorizeTenant($folder);

            DB::beginTransaction();

            try {
                // Move files to root
                $folder->media()->update(['folder_id' => null]);

                $folderName = $folder->name;

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => MediaFolder::class,
                    'subject_id' => $folder->id,
                    'action' => 'deleted_media_folder',
                    'description' => "Deleted media folder: {$folderName}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $folder->delete();

                DB::commit();

                return redirect()->route('admin.media.folders')
                    ->with('success', "Folder '{$folderName}' has been deleted.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting folder: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete folder.');
        }
    }

    /**
     * Authorize folder belongs to tenant.
     */
    protected function authorizeTenant(MediaFolder $folder)
    {
        if ($folder->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Format file size.
     */
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}