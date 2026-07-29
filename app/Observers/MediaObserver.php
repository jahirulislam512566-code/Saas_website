<?php

namespace App\Observers;

use App\Models\Media;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaObserver extends BaseObserver
{
    /**
     * Handle the Media "creating" event.
     */
    public function creating(Media $media): void
    {
        // Set default collection
        if (empty($media->collection)) {
            $media->collection = 'default';
        }

        // Set default disk
        if (empty($media->disk)) {
            $media->disk = config('filesystems.default');
        }

        $this->log('creating', $media);
    }

    /**
     * Handle the Media "created" event.
     */
    public function created(Media $media): void
    {
        Cache::forget('media_count');
        Cache::forget('media_list');
        
        $this->createAuditLog($media, 'created');

        $this->log('created', $media);
    }

    /**
     * Handle the Media "updating" event.
     */
    public function updating(Media $media): void
    {
        $this->log('updating', $media);
    }

    /**
     * Handle the Media "updated" event.
     */
    public function updated(Media $media): void
    {
        Cache::forget("media_{$media->id}");
        Cache::forget('media_list');
        
        $this->createAuditLog($media, 'updated', $media->getChanges());

        $this->log('updated', $media);
    }

    /**
     * Handle the Media "deleting" event.
     */
    public function deleting(Media $media): void
    {
        // Delete the actual file from storage
        $this->deleteFile($media);

        $this->log('deleting', $media);
    }

    /**
     * Handle the Media "deleted" event.
     */
    public function deleted(Media $media): void
    {
        Cache::forget("media_{$media->id}");
        Cache::forget('media_count');
        Cache::forget('media_list');

        $this->log('deleted', $media);
    }

    /**
     * Handle the Media "restored" event.
     */
    public function restored(Media $media): void
    {
        Cache::forget("media_{$media->id}");
        Cache::forget('media_list');

        $this->log('restored', $media);
    }

    /**
     * Handle the Media "forceDeleted" event.
     */
    public function forceDeleted(Media $media): void
    {
        // Delete the actual file from storage
        $this->deleteFile($media);
        
        Cache::forget("media_{$media->id}");
        Cache::forget('media_count');
        Cache::forget('media_list');

        Log::warning('Media permanently deleted', [
            'media_id' => $media->id,
            'filename' => $media->filename,
            'path' => $media->path,
            'deleted_by' => $this->getUserId(),
        ]);
    }

    /**
     * Delete the actual file from storage.
     */
    protected function deleteFile(Media $media): void
    {
        try {
            if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            // Delete thumbnails if they exist
            $this->deleteThumbnails($media);
        } catch (\Exception $e) {
            Log::error('Failed to delete media file', [
                'media_id' => $media->id,
                'path' => $media->path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete thumbnails for the media.
     */
    protected function deleteThumbnails(Media $media): void
    {
        try {
            $pathInfo = pathinfo($media->path);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            $extension = $pathInfo['extension'] ?? '';

            // Delete thumbnails with common suffixes
            $thumbnails = Storage::disk($media->disk)->files($directory);
            
            foreach ($thumbnails as $thumbnail) {
                if (str_starts_with(basename($thumbnail), $filename . '_thumb')) {
                    Storage::disk($media->disk)->delete($thumbnail);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete thumbnails', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Media $media, string $action, array $changes = []): void
    {
        // Create audit log entry if you have an AuditLog model
        // \App\Models\AuditLog::create([
        //     'model_type' => get_class($media),
        //     'model_id' => $media->id,
        //     'action' => $action,
        //     'changes' => $changes,
        //     'user_id' => $this->getUserId(),
        //     'ip_address' => $this->getIpAddress(),
        // ]);
    }
}