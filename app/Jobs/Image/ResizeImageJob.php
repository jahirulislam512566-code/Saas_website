<?php

namespace App\Jobs\Image;

use App\Jobs\BaseJob;
use App\Models\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ResizeImageJob extends BaseJob
{
    public Media $media;
    public int $width;
    public int $height;
    public bool $aspectRatio;
    public bool $upsize;

    public function __construct(
        Media $media,
        int $width = 800,
        int $height = null,
        bool $aspectRatio = true,
        bool $upsize = false
    ) {
        $this->media = $media;
        $this->width = $width;
        $this->height = $height ?? $width;
        $this->aspectRatio = $aspectRatio;
        $this->upsize = $upsize;
    }

    public function handle(): void
    {
        $this->logStart();

        try {
            $disk = Storage::disk($this->media->disk);
            
            if (!$disk->exists($this->media->path)) {
                throw new \Exception("Image file not found: {$this->media->path}");
            }

            // Load image
            $image = Image::make($disk->get($this->media->path));

            // Resize
            if ($this->aspectRatio) {
                $image->resize($this->width, $this->height, function ($constraint) {
                    $constraint->aspectRatio();
                    if (!$this->upsize) {
                        $constraint->upsize();
                    }
                });
            } else {
                $image->resize($this->width, $this->height);
            }

            // Save resized image
            $resizedPath = $this->getResizedPath();
            $disk->put($resizedPath, (string) $image);

            // Create thumbnail
            $this->createThumbnail($image, $disk);

            // Update media record
            $this->media->update([
                'width' => $image->width(),
                'height' => $image->height(),
                'resized_path' => $resizedPath,
                'resized_at' => now(),
            ]);

            $this->logComplete();

        } catch (\Exception $e) {
            $this->logError($e);
            throw $e;
        }
    }

    protected function getResizedPath(): string
    {
        $pathInfo = pathinfo($this->media->path);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'jpg';

        return "{$directory}/resized/{$filename}_{$this->width}x{$this->height}.{$extension}";
    }

    protected function createThumbnail($image, $disk): void
    {
        $thumbnailWidth = 200;
        $thumbnailHeight = 200;

        $thumbnail = clone $image;
        $thumbnail->fit($thumbnailWidth, $thumbnailHeight);

        $pathInfo = pathinfo($this->media->path);
        $thumbPath = "{$pathInfo['dirname']}/thumbnails/thumb_{$pathInfo['filename']}.{$pathInfo['extension']}";

        $disk->put($thumbPath, (string) $thumbnail);
    }

    protected function getJobData(): array
    {
        return [
            'media_id' => $this->media->id,
            'width' => $this->width,
            'height' => $this->height,
            'aspect_ratio' => $this->aspectRatio,
        ];
    }
}