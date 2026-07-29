<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class OptimizeImageAction
{
    /**
     * Optimize an image
     *
     * @param Media $media
     * @param array $data
     * @return Media
     * @throws \Exception
     */
    public function execute(Media $media, array $data = []): Media
    {
        return DB::transaction(function () use ($media, $data) {
            // Check if file exists
            if (!Storage::disk($media->disk)->exists($media->path)) {
                throw new \Exception('Image file not found.');
            }

            // Load image
            $image = Image::make(Storage::disk($media->disk)->path($media->path));

            // Optimize settings
            $quality = $data['quality'] ?? 80;
            $maxWidth = $data['max_width'] ?? 1920;
            $maxHeight = $data['max_height'] ?? 1080;

            // Resize if needed
            if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                $image->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Optimize based on format
            $format = $data['format'] ?? $this->detectOptimalFormat($image);
            
            if ($format === 'webp') {
                $image->encode('webp', $quality);
                $newExtension = 'webp';
            } elseif ($format === 'png') {
                $image->encode('png', $quality);
                $newExtension = 'png';
            } else {
                $image->encode('jpg', $quality);
                $newExtension = 'jpg';
            }

            // Save optimized image
            $newPath = str_replace(
                pathinfo($media->path, PATHINFO_EXTENSION),
                $newExtension,
                $media->path
            );

            Storage::disk($media->disk)->put($newPath, (string) $image);

            // Update media record
            $oldSize = $media->size;
            $newSize = strlen((string) $image);

            $media->update([
                'path' => $newPath,
                'filename' => str_replace(
                    pathinfo($media->filename, PATHINFO_EXTENSION),
                    $newExtension,
                    $media->filename
                ),
                'size' => $newSize,
                'optimized_at' => now(),
                'optimization_data' => [
                    'original_size' => $oldSize,
                    'optimized_size' => $newSize,
                    'compression_ratio' => round(($oldSize - $newSize) / $oldSize * 100, 2),
                    'quality' => $quality,
                    'format' => $format,
                ],
            ]);

            // Delete original if optimized version is smaller
            if ($newSize < $oldSize) {
                Storage::disk($media->disk)->delete($media->path);
            }

            Log::info('Image optimized', [
                'media_id' => $media->id,
                'old_size' => $oldSize,
                'new_size' => $newSize,
                'compression' => $media->optimization_data['compression_ratio'] . '%',
            ]);

            return $media->fresh();
        });
    }

    private function detectOptimalFormat($image): string
    {
        $mime = $image->mime();
        
        if (in_array($mime, ['image/png', 'image/gif'])) {
            return 'png';
        }
        
        // Use webp for better compression
        return 'webp';
    }
}