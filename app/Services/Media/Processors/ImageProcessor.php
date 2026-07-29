<?php

namespace App\Services\Media\Processors;

use App\Services\Media\Processors\ProcessorInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;

class ImageProcessor implements ProcessorInterface
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'quality' => 80,
            'max_width' => 1920,
            'max_height' => 1080,
            'thumbnail_width' => 300,
            'thumbnail_height' => 300,
            'cache_path' => storage_path('app/temp'),
        ], $config);
    }

    /**
     * Process an image file
     */
    public function process(UploadedFile $file, array $options = []): ?UploadedFile
    {
        try {
            // Merge options with defaults
            $options = array_merge($this->config, $options);

            // Validate file
            $this->validateFile($file);

            // Load image
            $image = Image::make($file);

            // Apply processing
            $this->applyResize($image, $options);
            $this->applyFit($image, $options);
            $this->applyOptimization($image, $options);
            $this->applyWatermark($image, $options);

            // Generate thumbnail if needed
            if ($options['thumbnail'] ?? false) {
                $this->generateThumbnail($image, $file, $options);
            }

            // Generate processed path
            $processedPath = $this->generateProcessedPath($file, $options);
            
            // Ensure directory exists
            $this->ensureDirectoryExists(dirname($processedPath));

            // Save processed image
            $image->save($processedPath);

            // Create new UploadedFile instance
            return new UploadedFile(
                $processedPath,
                $file->getClientOriginalName(),
                $file->getMimeType(),
                null,
                true
            );

        } catch (\Exception $e) {
            Log::error('Image processing failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new \RuntimeException('Image processing failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Validate the uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('Invalid file upload');
        }

        // Check file size (max 20MB)
        $maxSize = $this->config['max_file_size'] ?? 20 * 1024 * 1024; // 20MB default
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException(
                sprintf('File size exceeds maximum allowed size of %s', $this->formatSize($maxSize))
            );
        }

        // Check image type
        $allowedTypes = $this->config['allowed_types'] ?? ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mimeType = $file->getMimeType();
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new \InvalidArgumentException(
                sprintf('File type %s is not allowed. Allowed types: %s', 
                    $mimeType, 
                    implode(', ', $allowedTypes)
                )
            );
        }

        // Get image dimensions
        list($width, $height) = getimagesize($file->getRealPath());
        
        // Check dimensions
        $maxWidth = $this->config['max_width'] ?? 1920;
        $maxHeight = $this->config['max_height'] ?? 1080;
        
        if ($width > $maxWidth || $height > $maxHeight) {
            throw new \InvalidArgumentException(
                sprintf('Image dimensions (%dx%d) exceed maximum allowed (%dx%d)', 
                    $width, $height, $maxWidth, $maxHeight
                )
            );
        }
    }

    /**
     * Apply resize to image
     */
    protected function applyResize(InterventionImage $image, array $options): void
    {
        if (!($options['resize'] ?? false)) {
            return;
        }

        $width = $options['width'] ?? $this->config['max_width'];
        $height = $options['height'] ?? null;
        $aspectRatio = $options['aspect_ratio'] ?? true;
        $upsize = $options['upsize'] ?? false;

        if ($aspectRatio) {
            $image->resize($width, $height, function ($constraint) use ($upsize) {
                $constraint->aspectRatio();
                if (!$upsize) {
                    $constraint->upsize();
                }
            });
        } else {
            $image->resize($width, $height);
        }
    }

    /**
     * Apply fit to image (exact dimensions)
     */
    protected function applyFit(InterventionImage $image, array $options): void
    {
        if (!($options['fit'] ?? false)) {
            return;
        }

        $width = $options['fit_width'] ?? $options['width'] ?? $this->config['max_width'];
        $height = $options['fit_height'] ?? $options['height'] ?? $this->config['max_height'];
        
        $image->fit($width, $height);
    }

    /**
     * Apply optimization to image
     */
    protected function applyOptimization(InterventionImage $image, array $options): void
    {
        if (!($options['optimize'] ?? true)) {
            return;
        }

        $quality = (int) ($options['quality'] ?? $this->config['quality']);
        $format = $options['format'] ?? $this->detectOptimalFormat($image);

        $image->encode($format, $quality);
    }

    /**
     * Apply watermark to image
     */
    protected function applyWatermark(InterventionImage $image, array $options): void
    {
        if (!($options['watermark'] ?? false)) {
            return;
        }

        $watermarkPath = $options['watermark_path'] ?? public_path('images/watermark.png');
        
        if (!file_exists($watermarkPath)) {
            Log::warning('Watermark file not found', ['path' => $watermarkPath]);
            return;
        }

        try {
            $watermark = Image::make($watermarkPath);
            $position = $options['watermark_position'] ?? 'bottom-right';
            $opacity = (int) ($options['watermark_opacity'] ?? 50);
            $size = (int) ($options['watermark_size'] ?? 20); // percentage of image

            // Resize watermark to percentage of image
            $imageWidth = $image->width();
            $watermarkWidth = ($size / 100) * $imageWidth;
            $watermark->resize($watermarkWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $watermark->opacity($opacity);
            $image->insert($watermark, $position);

        } catch (\Exception $e) {
            Log::warning('Watermark application failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate thumbnail
     */
    protected function generateThumbnail(InterventionImage $image, UploadedFile $file, array $options): array
    {
        $thumbWidth = (int) ($options['thumbnail_width'] ?? $this->config['thumbnail_width']);
        $thumbHeight = (int) ($options['thumbnail_height'] ?? $this->config['thumbnail_height']);

        $thumbnail = clone $image;
        $thumbnail->fit($thumbWidth, $thumbHeight);

        $thumbPath = $this->generateThumbPath($file, $options);
        
        // Ensure directory exists
        $this->ensureDirectoryExists(dirname($thumbPath));
        
        $thumbnail->save($thumbPath);

        return [
            'path' => $thumbPath,
            'width' => $thumbWidth,
            'height' => $thumbHeight,
        ];
    }

    /**
     * Generate processed file path
     */
    protected function generateProcessedPath(UploadedFile $file, array $options): string
    {
        $basePath = $options['cache_path'] ?? $this->config['cache_path'];
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $width = $options['width'] ?? $this->config['max_width'];
        
        // Sanitize filename
        $filename = $this->sanitizeFilename($filename);
        
        return "{$basePath}/processed_{$filename}_{$width}.{$extension}";
    }

    /**
     * Generate thumbnail path
     */
    protected function generateThumbPath(UploadedFile $file, array $options): string
    {
        $basePath = $options['cache_path'] ?? $this->config['cache_path'];
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        // Sanitize filename
        $filename = $this->sanitizeFilename($filename);
        
        return "{$basePath}/thumbnails/thumb_{$filename}.{$extension}";
    }

    /**
     * Ensure directory exists
     */
    protected function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Detect optimal image format
     */
    protected function detectOptimalFormat(InterventionImage $image): string
    {
        $mime = $image->mime();
        
        // Use WebP for better compression if supported
        if (in_array($mime, ['image/jpeg', 'image/png'])) {
            return 'webp';
        }
        
        if ($mime === 'image/gif') {
            return 'gif';
        }

        return 'jpg';
    }

    /**
     * Sanitize filename
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        // Trim underscores
        $filename = trim($filename, '_');
        
        return $filename ?: 'image';
    }

    /**
     * Format file size
     */
    protected function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get processor configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get processor name
     */
    public function getProcessorName(): string
    {
        return 'image';
    }
}