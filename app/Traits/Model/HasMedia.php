<?php

namespace App\Traits\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait HasMedia
{
    /**
     * Get the media relationship.
     */
    public function media()
    {
        return $this->morphMany(\App\Models\Media::class, 'model');
    }

    /**
     * Get media by collection.
     */
    public function getMedia(string $collection = 'default')
    {
        return $this->media()->where('collection', $collection)->get();
    }

    /**
     * Get first media by collection.
     */
    public function getFirstMedia(string $collection = 'default')
    {
        return $this->media()->where('collection', $collection)->first();
    }

    /**
     * Get media URL by collection.
     */
    public function getMediaUrl(string $collection = 'default'): ?string
    {
        $media = $this->getFirstMedia($collection);
        return $media ? $media->url : null;
    }

    /**
     * Add media.
     */
    public function addMedia(UploadedFile $file, string $collection = 'default', array $options = []): \App\Models\Media
    {
        return $this->media()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $file->store("uploads/{$this->getTable()}/{$this->id}", 'public'),
            'collection' => $collection,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => 'public',
            'options' => $options,
        ]);
    }

    /**
     * Add media from URL.
     */
    public function addMediaFromUrl(string $url, string $collection = 'default', array $options = []): \App\Models\Media
    {
        $contents = file_get_contents($url);
        $filename = basename($url);
        
        $path = Storage::disk('public')->put("uploads/{$this->getTable()}/{$this->id}/{$filename}", $contents);

        return $this->media()->create([
            'filename' => $filename,
            'path' => $path,
            'collection' => $collection,
            'mime_type' => mime_content_type($url),
            'size' => strlen($contents),
            'disk' => 'public',
            'options' => $options,
        ]);
    }

    /**
     * Delete all media.
     */
    public function deleteAllMedia(): void
    {
        foreach ($this->media as $media) {
            $media->delete();
        }
    }

    /**
     * Clear media collection.
     */
    public function clearMediaCollection(string $collection = 'default'): void
    {
        foreach ($this->media()->where('collection', $collection)->get() as $media) {
            $media->delete();
        }
    }

    /**
     * Check if model has media.
     */
    public function hasMedia(string $collection = 'default'): bool
    {
        return $this->media()->where('collection', $collection)->exists();
    }
}