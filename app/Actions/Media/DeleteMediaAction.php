<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteMediaAction
{
    /**
     * Delete media
     *
     * @param Media $media
     * @param bool $forceDelete
     * @return bool
     */
    public function execute(Media $media, bool $forceDelete = false): bool
    {
        return DB::transaction(function () use ($media, $forceDelete) {
            // Delete file from storage
            if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            // Delete thumbnails if exist
            $this->deleteThumbnails($media);

            if ($forceDelete) {
                $media->forceDelete();
                Log::warning('Media permanently deleted', [
                    'media_id' => $media->id,
                    'filename' => $media->filename,
                    'deleted_by' => auth()->id()
                ]);
            } else {
                $media->delete();
                Log::info('Media soft deleted', [
                    'media_id' => $media->id,
                    'filename' => $media->filename,
                    'deleted_by' => auth()->id()
                ]);
            }

            return true;
        });
    }

    private function deleteThumbnails(Media $media): void
    {
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
    }
}