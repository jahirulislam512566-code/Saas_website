<?php

namespace App\Services\Media;

use App\Models\Media;
use App\Models\Website;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaService
{
    public function uploadMedia(Website $website, $file, array $data = []): Media
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $extension;
        
        $path = $file->storeAs("websites/{$website->id}/media", $fileName, 'public');
        
        $media = Media::create([
            'website_id' => $website->id,
            'name' => $data['name'] ?? $originalName,
            'file_name' => $fileName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $this->getMediaType($file->getMimeType()),
            'alt_text' => $data['alt_text'] ?? null,
            'settings' => $data['settings'] ?? [],
        ]);

        if ($this->isImage($file->getMimeType())) {
            $this->generateThumbnails($media);
        }

        return $media;
    }

    public function deleteMedia(Media $media): bool
    {
        Storage::disk('public')->delete($media->path);
        
        // Delete thumbnails
        if ($this->isImage($media->mime_type)) {
            Storage::disk('public')->delete($this->getThumbnailPaths($media));
        }
        
        return $media->delete();
    }

    public function getWebsiteMedia(Website $website)
    {
        return $website->media()->orderBy('created_at', 'desc')->get();
    }

    public function getMediaByType(Website $website, string $type)
    {
        return $website->media()->where('type', $type)->get();
    }

    private function getMediaType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if (str_starts_with($mimeType, 'audio/')) return 'audio';
        return 'document';
    }

    private function isImage(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'image/');
    }

    private function generateThumbnails(Media $media): void
    {
        $sizes = [
            'thumbnail' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ];

        $path = Storage::disk('public')->path($media->path);
        $directory = dirname($media->path);
        $filename = pathinfo($media->file_name, PATHINFO_FILENAME);
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);

        foreach ($sizes as $size => $dimensions) {
            $image = Image::make($path);
            $image->fit($dimensions[0], $dimensions[1]);
            
            $thumbPath = "{$directory}/{$size}_{$filename}.{$extension}";
            $image->save(Storage::disk('public')->path($thumbPath));
            
            $media->thumbnails[$size] = $thumbPath;
        }
        
        $media->save();
    }

    private function getThumbnailPaths(Media $media): array
    {
        $paths = [];
        foreach ($media->thumbnails ?? [] as $size => $path) {
            $paths[] = $path;
        }
        return $paths;
    }

    public function getMediaUrl(Media $media, string $size = null): string
    {
        if ($size && isset($media->thumbnails[$size])) {
            return Storage::disk('public')->url($media->thumbnails[$size]);
        }
        return Storage::disk('public')->url($media->path);
    }
}