<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadImageAction
{
    /**
     * Upload an image
     *
     * @param array $data
     * @return Media
     * @throws \Exception
     */
    public function execute(array $data): Media
    {
        return DB::transaction(function () use ($data) {
            /** @var UploadedFile $file */
            $file = $data['file'];
            
            // Validate file
            $this->validateFile($file, $data);

            // Generate filename
            $filename = $this->generateFilename($file);
            
            // Store file
            $path = $file->storeAs(
                $this->getStoragePath($data),
                $filename,
                $data['disk'] ?? config('filesystems.default')
            );

            if (!$path) {
                throw new \Exception('Failed to upload file.');
            }

            // Get image dimensions
            $dimensions = $this->getImageDimensions($file);

            // Create media record
            $media = Media::create([
                'name' => $data['name'] ?? $file->getClientOriginalName(),
                'filename' => $filename,
                'path' => $path,
                'disk' => $data['disk'] ?? config('filesystems.default'),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $dimensions['width'] ?? null,
                'height' => $dimensions['height'] ?? null,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'type' => 'image',
                'collection' => $data['collection'] ?? 'default',
                'model_type' => $data['model_type'] ?? null,
                'model_id' => $data['model_id'] ?? null,
                'order' => $data['order'] ?? 0,
                'uploaded_by' => auth()->id(),
            ]);

            Log::info('Image uploaded', [
                'media_id' => $media->id,
                'filename' => $filename,
                'path' => $path,
                'size' => $file->getSize(),
                'uploaded_by' => auth()->id()
            ]);

            return $media;
        });
    }

    private function validateFile(UploadedFile $file, array $data): void
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $maxSize = $data['max_size'] ?? 5 * 1024 * 1024; // 5MB default

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Invalid file type. Allowed: JPEG, PNG, GIF, WEBP, SVG');
        }

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds maximum allowed size.');
        }
    }

    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(6);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    private function getStoragePath(array $data): string
    {
        $base = $data['base_path'] ?? 'uploads';
        $collection = $data['collection'] ?? 'default';
        $year = now()->format('Y');
        $month = now()->format('m');
        
        return "{$base}/{$collection}/{$year}/{$month}";
    }

    private function getImageDimensions(UploadedFile $file): array
    {
        try {
            $imageInfo = getimagesize($file->getRealPath());
            return [
                'width' => $imageInfo[0] ?? null,
                'height' => $imageInfo[1] ?? null,
            ];
        } catch (\Exception $e) {
            return ['width' => null, 'height' => null];
        }
    }
}