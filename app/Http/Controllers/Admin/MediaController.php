<?php
// app/Http/Controllers/Admin/MediaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaFolder;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Display media library.
     */
    public function library(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Media::forTenant($tenantId)->with('folder');

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('file_name', 'LIKE', "%{$search}%")
                      ->orWhere('alt_text', 'LIKE', "%{$search}%");
                });
            }

            // Type filter
            if ($request->filled('type')) {
                switch ($request->type) {
                    case 'image':
                        $query->where('mime_type', 'LIKE', 'image/%');
                        break;
                    case 'video':
                        $query->where('mime_type', 'LIKE', 'video/%');
                        break;
                    case 'audio':
                        $query->where('mime_type', 'LIKE', 'audio/%');
                        break;
                    case 'document':
                        $query->whereIn('mime_type', [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        ]);
                        break;
                }
            }

            // Folder filter
            if ($request->filled('folder')) {
                $query->where('folder_id', $request->folder);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $allowedSorts = ['id', 'name', 'size', 'created_at'];
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $media = $query->paginate(24)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Media::forTenant($tenantId)->count(),
                'images' => Media::forTenant($tenantId)->where('mime_type', 'LIKE', 'image/%')->count(),
                'videos' => Media::forTenant($tenantId)->where('mime_type', 'LIKE', 'video/%')->count(),
                'storage' => $this->formatSize(Media::forTenant($tenantId)->sum('size')),
            ];

            // Get folders
            $folders = MediaFolder::forTenant($tenantId)->get();

            // Get notification variables for top-nav
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications()->count();
            $notifications = $user->notifications()->latest()->limit(5)->get();

            return view('admin.media.library', compact(
                'media',
                'stats',
                'folders',
                'unreadNotifications',
                'notifications'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching media: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch media. Please try again.');
        }
    }

    /**
     * Show upload form.
     */
    public function uploadForm()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $folders = MediaFolder::forTenant($tenantId)->get();
            $recentUploads = Media::forTenant($tenantId)
                ->latest()
                ->limit(10)
                ->get();

            return view('admin.media.upload', compact('folders', 'recentUploads'));
        } catch (\Exception $e) {
            Log::error('Error loading upload form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load upload form.');
        }
    }

    /**
     * Upload media files.
     */
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => ['required', 'file', 'max:20480'], // 20MB max
                'folder_id' => ['nullable', 'exists:media_folders,id'],
                'visibility' => ['nullable', 'in:public,private'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $file = $request->file('file');
            $tenantId = auth()->user()->tenant_id;

            // Generate unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName) . '-' . time() . '.' . $extension;

            // Store file
            $path = $file->storeAs(
                'media/' . $tenantId . '/' . ($request->folder_id ?? 'root'),
                $fileName,
                'public'
            );

            $media = Media::create([
                'tenant_id' => $tenantId,
                'folder_id' => $request->folder_id,
                'user_id' => auth()->id(),
                'name' => $originalName,
                'file_name' => $fileName,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'visibility' => $request->visibility ?? 'public',
                'metadata' => [
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $extension,
                ],
            ]);

            // Generate thumbnail for images
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $this->generateThumbnail($media);
            }

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $tenantId,
                'subject_type' => Media::class,
                'subject_id' => $media->id,
                'action' => 'uploaded_media',
                'description' => "Uploaded media: {$media->name}",
                'properties' => [
                    'file_name' => $media->file_name,
                    'file_size' => $media->size,
                    'mime_type' => $media->mime_type,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $media,
                'url' => $media->url,
                'message' => 'File uploaded successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading media: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk upload media files.
     */
    public function bulkUpload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => ['required', 'array'],
                'files.*' => ['file', 'max:20480'],
                'folder_id' => ['nullable', 'exists:media_folders,id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $uploaded = [];
            $failed = [];

            foreach ($request->file('files') as $file) {
                try {
                    $result = $this->processUpload($file, $request->folder_id);
                    if ($result['success']) {
                        $uploaded[] = $result['data'];
                    } else {
                        $failed[] = $result['message'];
                    }
                } catch (\Exception $e) {
                    $failed[] = $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'uploaded' => $uploaded,
                'failed' => $failed,
                'message' => count($uploaded) . ' files uploaded successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk upload: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload files: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process single file upload.
     */
    private function processUpload($file, $folderId = null)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($originalName) . '-' . time() . '.' . $extension;

            $path = $file->storeAs(
                'media/' . $tenantId . '/' . ($folderId ?? 'root'),
                $fileName,
                'public'
            );

            $media = Media::create([
                'tenant_id' => $tenantId,
                'folder_id' => $folderId,
                'user_id' => auth()->id(),
                'name' => $originalName,
                'file_name' => $fileName,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'visibility' => 'public',
                'metadata' => [
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $extension,
                ],
            ]);

            if (str_starts_with($file->getMimeType(), 'image/')) {
                $this->generateThumbnail($media);
            }

            return ['success' => true, 'data' => $media];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Delete media.
     */
    public function destroy(Media $media)
    {
        try {
            $this->authorizeTenant($media);

            DB::beginTransaction();

            try {
                // Delete file from storage
                if (Storage::disk($media->disk)->exists($media->path)) {
                    Storage::disk($media->disk)->delete($media->path);
                }

                // Delete thumbnail if exists
                if ($media->thumbnail_path && Storage::disk($media->disk)->exists($media->thumbnail_path)) {
                    Storage::disk($media->disk)->delete($media->thumbnail_path);
                }

                $mediaName = $media->name;

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Media::class,
                    'subject_id' => $media->id,
                    'action' => 'deleted_media',
                    'description' => "Deleted media: {$mediaName}",
                    'properties' => [
                        'file_name' => $media->file_name,
                        'file_size' => $media->size,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $media->delete();

                DB::commit();

                return redirect()->route('admin.media.library')
                    ->with('success', "Media '{$mediaName}' has been deleted successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting media: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete media.');
        }
    }

    /**
     * Bulk delete media.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['exists:media,id'],
            ]);

            $media = Media::whereIn('id', $request->ids)->get();

            DB::beginTransaction();

            try {
                foreach ($media as $item) {
                    if (Storage::disk($item->disk)->exists($item->path)) {
                        Storage::disk($item->disk)->delete($item->path);
                    }
                    if ($item->thumbnail_path && Storage::disk($item->disk)->exists($item->thumbnail_path)) {
                        Storage::disk($item->disk)->delete($item->thumbnail_path);
                    }
                    $item->delete();
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => count($media) . ' files deleted successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error bulk deleting media: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete files.',
            ], 500);
        }
    }

    /**
     * Get media for editing.
     */
    public function edit(Media $media)
    {
        try {
            $this->authorizeTenant($media);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'alt_text' => $media->alt_text,
                    'description' => $media->description,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching media data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch media data.',
            ], 500);
        }
    }

    /**
     * Update media.
     */
    public function update(Request $request, Media $media)
    {
        try {
            $this->authorizeTenant($media);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'alt_text' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $media->update([
                'name' => $request->name,
                'alt_text' => $request->alt_text,
                'description' => $request->description,
            ]);

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Media::class,
                'subject_id' => $media->id,
                'action' => 'updated_media',
                'description' => "Updated media: {$media->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.media.library')
                ->with('success', "Media '{$media->name}' has been updated successfully.");
        } catch (\Exception $e) {
            Log::error('Error updating media: ' . $e->getMessage());
            return back()->with('error', 'Failed to update media.');
        }
    }

    /**
     * Authorize media belongs to tenant.
     */
    protected function authorizeTenant(Media $media)
    {
        if ($media->tenant_id !== auth()->user()->tenant_id) {
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

    /**
     * Generate thumbnail for image.
     */
    private function generateThumbnail($media)
    {
        try {
            if (!str_starts_with($media->mime_type, 'image/')) {
                return;
            }

            $path = Storage::disk($media->disk)->path($media->path);
            $thumbnailPath = 'thumbnails/' . $media->tenant_id . '/' . $media->file_name;
            
            // Create thumbnail using Intervention Image or similar
            // This is a simplified example
            $image = \Intervention\Image\Facades\Image::make($path);
            $image->fit(300, 300);
            
            $thumbnailFullPath = Storage::disk($media->disk)->path($thumbnailPath);
            $image->save($thumbnailFullPath);
            
            $media->update([
                'thumbnail_path' => $thumbnailPath,
                'thumbnail_url' => Storage::disk($media->disk)->url($thumbnailPath),
            ]);
        } catch (\Exception $e) {
            Log::warning('Could not generate thumbnail for media ID ' . $media->id . ': ' . $e->getMessage());
        }
    }
}