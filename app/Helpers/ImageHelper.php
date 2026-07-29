<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    /**
     * Upload image
     */
    public static function upload($file, $path = 'uploads', $options = []): array
    {
        if (!$file instanceof UploadedFile) {
            return ['error' => 'Invalid file'];
        }

        $options = array_merge([
            'resize' => true,
            'width' => 1200,
            'height' => null,
            'quality' => 80,
            'thumbnails' => true,
            'thumbnail_width' => 300,
            'thumbnail_height' => 300,
            'disk' => 'public',
            'filename' => null,
            'optimize' => true,
        ], $options);

        // Validate file
        if (!in_array($file->getMimeType(), [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'
        ])) {
            return ['error' => 'Invalid image type'];
        }

        // Generate filename
        $filename = $options['filename'] ?? self::generateFilename($file);
        $extension = $file->getClientOriginalExtension();
        $filename = $filename . '.' . $extension;

        // Create path
        $fullPath = $path . '/' . date('Y') . '/' . date('m');

        // Resize and save
        $image = Image::make($file);

        if ($options['resize'] && $options['width']) {
            if ($options['height']) {
                $image->fit($options['width'], $options['height']);
            } else {
                $image->resize($options['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        }

        // Optimize
        if ($options['optimize']) {
            $image->encode(null, $options['quality']);
        }

        // Save
        $disk = Storage::disk($options['disk']);
        $saved = $disk->put($fullPath . '/' . $filename, (string) $image);

        if (!$saved) {
            return ['error' => 'Failed to save image'];
        }

        $result = [
            'path' => $fullPath . '/' . $filename,
            'url' => $disk->url($fullPath . '/' . $filename),
            'filename' => $filename,
            'size' => $disk->size($fullPath . '/' . $filename),
            'mime' => $file->getMimeType(),
        ];

        // Generate thumbnails
        if ($options['thumbnails']) {
            $thumbnail = self::createThumbnail(
                $image,
                $fullPath,
                $filename,
                $options['thumbnail_width'],
                $options['thumbnail_height'],
                $options['disk']
            );

            if ($thumbnail) {
                $result['thumbnail'] = $thumbnail;
            }
        }

        return $result;
    }

    /**
     * Create thumbnail
     */
    protected static function createThumbnail($image, $path, $filename, $width, $height, $disk): ?array
    {
        try {
            $thumbnail = clone $image;
            $thumbnail->fit($width, $height);

            $thumbFilename = 'thumb_' . $filename;
            $thumbPath = $path . '/thumbnails/' . $thumbFilename;

            $disk = Storage::disk($disk);
            $saved = $disk->put($thumbPath, (string) $thumbnail);

            if ($saved) {
                return [
                    'path' => $thumbPath,
                    'url' => $disk->url($thumbPath),
                    'filename' => $thumbFilename,
                    'size' => $disk->size($thumbPath),
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate filename
     */
    protected static function generateFilename($file): string
    {
        return date('Ymd_His') . '_' . Str::random(6);
    }

    /**
     * Delete image
     */
    public static function delete($path, $disk = 'public'): bool
    {
        $disk = Storage::disk($disk);

        if ($disk->exists($path)) {
            // Delete thumbnails
            $pathInfo = pathinfo($path);
            $thumbPath = $pathInfo['dirname'] . '/thumbnails/thumb_' . $pathInfo['basename'];

            if ($disk->exists($thumbPath)) {
                $disk->delete($thumbPath);
            }

            return $disk->delete($path);
        }

        return false;
    }

    /**
     * Get image dimensions
     */
    public static function dimensions($path, $disk = 'public'): ?array
    {
        try {
            $disk = Storage::disk($disk);
            $image = Image::make($disk->get($path));

            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'ratio' => $image->width() / $image->height(),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Optimize existing image
     */
    public static function optimize($path, $quality = 80, $disk = 'public'): bool
    {
        try {
            $disk = Storage::disk($disk);
            $image = Image::make($disk->get($path));
            $image->encode(null, $quality);

            return $disk->put($path, (string) $image);
        } catch (\Exception $e) {
            return false;
        }
    }
}