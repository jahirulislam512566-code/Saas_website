<?php

namespace App\Jobs\Image;

use App\Jobs\BaseJob;
use App\Models\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class OptimizeImageJob extends BaseJob
{
    public Media $media;
    public int $quality;
    public ?string $format;

    public function __construct(Media $media, int $quality = 80, ?string $format = null)
    {
        $this->media = $media;
        $this->quality = $quality;
        $this->format = $format;
    }

    public function handle(): void
    {
        $this->logStart();

        try {
            $disk = Storage::disk($this->media->disk);
            
            if (!$disk->exists($this->media->path)) {
                throw new \Exception("Image file not found: {$this->media->path}");
            }

            $image = Image::make($disk->get($this->media->path));
            
            $originalSize = $disk->size($this->media->path);
            $format = $this->format ?? $this->detectOptimalFormat($image);

            // Optimize
            $image->encode($format, $this->quality);

            // Save optimized image
            $optimizedPath = $this->getOptimizedPath($format);
            $disk->put($optimizedPath, (string) $image);

            $optimizedSize = strlen((string) $image);

            // Update media record
            $this->media->update([
                'path' => $optimizedPath,
                'size' => $optimizedSize,
                'optimized_at' => now(),
                'optimization_data' => [
                    'original_size' => $originalSize,
                    'optimized_size' => $optimizedSize,
                    'compression_ratio' => round(($originalSize - $optimizedSize) / $originalSize * 100, 2),
                    'quality' => $this->quality,
                    'format' => $format,
                ],
            ]);

            // Delete original if optimized is smaller
            if ($optimizedSize < $originalSize) {
                $disk->delete($this->media->getOriginalPath());
            }

            $this->logComplete();

        } catch (\Exception $e) {
            $this->logError($e);
            throw $e;
        }
    }

    protected function detectOptimalFormat($image): string
    {
        $mime = $image->mime();
        
        // Use WebP for better compression
        if (in_array($mime, ['image/jpeg', 'image/png'])) {
            return 'webp';
        }

        return 'jpg';
    }

    protected function getOptimizedPath(string $format): string
    {
        $pathInfo = pathinfo($this->media->path);
        $extension = $format === 'webp' ? 'webp' : 'jpg';
        
        return "{$pathInfo['dirname']}/optimized/{$pathInfo['filename']}.{$extension}";
    }

    protected function getJobData(): array
    {
        return [
            'media_id' => $this->media->id,
            'quality' => $this->quality,
            'format' => $this->format,
        ];
    }
}