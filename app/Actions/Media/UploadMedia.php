<?php

namespace App\Actions\Media;

use App\Actions\BaseAction;
use App\Models\Media;
use App\Models\Website;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UploadMedia extends BaseAction
{
    protected array $validationRules = [
        'website_id' => 'required|exists:websites,id',
        'file' => 'required|file|max:20480', // 20MB
        'name' => 'nullable|string|max:255',
        'alt_text' => 'nullable|string|max:255',
        'caption' => 'nullable|string|max:500',
        'description' => 'nullable|string',
        'folder' => 'nullable|string|max:100',
        'generate_thumbnails' => 'nullable|boolean',
        'thumbnail_sizes' => 'nullable|array',
        'settings' => 'nullable|array',
    ];

    public function execute(array $data): Media
    {
        $this->data = $data;
        $this->validate($data);

        $this->beginTransaction();

        try {
            $website = Website::findOrFail($data['website_id']);
            $file = $data['file'];

            // Validate file type
            $this->validateFileType($file);

            // Check storage limits
            $this->checkStorageLimit($website, $file);

            // Generate file name
            $fileName = $this->generateFileName($file, $data);

            // Determine folder path
            $folder = $data['folder'] ?? 'media';
            $path = "websites/{$website->id}/{$folder}/{$fileName}";

            // Store file
            Storage::disk('public')->putFileAs(
                "websites/{$website->id}/{$folder}",
                $file,
                $fileName
            );

            // Create media record
            $media = Media::create([
                'website_id' => $website->id,
                'name' => $data['name'] ?? $file->getClientOriginalName(),
                'file_name' => $fileName,
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'type' => $this->getMediaType($file),
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'description' => $data['description'] ?? null,
                'settings' => $data['settings'] ?? [],
                'metadata' => [
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'uploaded_at' => now()->toDateTimeString(),
                    'uploaded_by' => auth()->id(),
                ],
            ]);

            // Generate thumbnails if image
            if ($this->isImage($file) && ($data['generate_thumbnails'] ?? true)) {
                $this->generateThumbnails(
                    $media,
                    $data['thumbnail_sizes'] ?? null
                );
            }

            // Clear cache
            Cache::forget('website_media_' . $website->id);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\MediaUploaded', [
                'media' => $media,
                'website' => $website,
                'user' => auth()->user(),
            ]);

            return $media->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            // Clean up uploaded file
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $this->logError('Failed to upload media', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function validateFileType(UploadedFile $file): void
    {
        $allowedTypes = config('media.allowed_types', [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/webm',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain', 'text/csv',
        ]);

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new ActionException(
                "File type not allowed: {$file->getMimeType()}",
                ['allowed_types' => $allowedTypes]
            );
        }
    }

    protected function checkStorageLimit(Website $website, UploadedFile $file): void
    {
        $subscription = $website->user->subscriptions()->where('status', 'active')->first();
        $maxStorage = $subscription?->plan->storage_limit ?? 100 * 1024 * 1024; // 100MB default
        
        $currentStorage = $website->media()->sum('size');
        $newTotal = $currentStorage + $file->getSize();
        
        if ($newTotal > $maxStorage) {
            throw new ActionException(
                "Storage limit exceeded. Current: " . $this->formatFileSize($currentStorage) . 
                ", Limit: " . $this->formatFileSize($maxStorage),
                [
                    'current' => $currentStorage,
                    'limit' => $maxStorage,
                    'new_total' => $newTotal,
                ]
            );
        }
    }

    protected function generateFileName(UploadedFile $file, array $data): string
    {
        if (isset($data['file_name'])) {
            return $data['file_name'];
        }

        $extension = $file->getClientOriginalExtension();
        $name = $data['name'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($name);
        
        // Ensure unique filename
        $fileName = $slug . '.' . $extension;
        $count = 1;
        
        while ($this->fileExists($fileName)) {
            $fileName = $slug . '-' . $count . '.' . $extension;
            $count++;
        }
        
        return $fileName;
    }

    protected function fileExists(string $fileName): bool
    {
        return Storage::disk('public')->exists("media/{$fileName}");
    }

    protected function getMediaType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if (str_starts_with($mimeType, 'audio/')) return 'audio';
        
        return 'document';
    }

    protected function isImage(UploadedFile $file): bool
    {
        return str_starts_with($file->getMimeType(), 'image/');
    }

    protected function generateThumbnails(Media $media, ?array $sizes = null): void
    {
        $sizes = $sizes ?? config('media.thumbnail_sizes', [
            'thumbnail' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ]);

        $path = Storage::disk('public')->path($media->path);
        $directory = dirname($media->path);
        $filename = pathinfo($media->file_name, PATHINFO_FILENAME);
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);

        $thumbnails = [];

        foreach ($sizes as $size => $dimensions) {
            try {
                $image = Image::make($path);
                $image->fit($dimensions[0], $dimensions[1], function ($constraint) {
                    $constraint->upsize();
                });

                $thumbPath = "{$directory}/{$size}_{$filename}.{$extension}";
                $image->save(Storage::disk('public')->path($thumbPath));

                $thumbnails[$size] = $thumbPath;
            } catch (\Exception $e) {
                // Log error but continue with other sizes
                \Log::error("Failed to generate thumbnail: " . $e->getMessage());
            }
        }

        if (!empty($thumbnails)) {
            $media->update(['thumbnails' => $thumbnails]);
        }
    }

    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}