<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Website;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class MediaRepository extends BaseRepository
{
    public function __construct(Media $media)
    {
        parent::__construct($media);
    }

    public function findByWebsite(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByWebsitePaginated(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('website_id', $website->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findByType(Website $website, string $type): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getImages(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('type', 'image')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getVideos(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('type', 'video')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDocuments(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('type', 'document')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createMedia(Website $website, array $data): Media
    {
        $data['website_id'] = $website->id;
        return $this->create($data);
    }

    public function updateMedia(Media $media, array $data): Media
    {
        return $this->update($media, $data);
    }

    public function deleteMedia(Media $media): bool
    {
        // Delete physical file
        Storage::disk('public')->delete($media->path);
        
        // Delete thumbnails
        if ($media->type === 'image' && $media->thumbnails) {
            foreach ($media->thumbnails as $thumbnail) {
                Storage::disk('public')->delete($thumbnail);
            }
        }
        
        return $this->delete($media);
    }

    public function deleteMultipleMedia(array $mediaIds): bool
    {
        $media = $this->model->whereIn('id', $mediaIds)->get();
        
        foreach ($media as $item) {
            $this->deleteMedia($item);
        }
        
        return true;
    }

    public function getMediaStats(Website $website): array
    {
        $media = $this->model->where('website_id', $website->id);
        
        return [
            'total' => $media->count(),
            'images' => $media->where('type', 'image')->count(),
            'videos' => $media->where('type', 'video')->count(),
            'documents' => $media->where('type', 'document')->count(),
            'total_size' => $media->sum('size'),
            'total_size_mb' => number_format($media->sum('size') / 1024 / 1024, 2),
        ];
    }

    public function getMediaUsage(Website $website): array
    {
        $media = $this->model->where('website_id', $website->id)->get();
        
        $usage = [
            'used_in_pages' => 0,
            'used_in_posts' => 0,
            'unused' => 0,
            'total' => $media->count(),
        ];
        
        foreach ($media as $item) {
            if ($item->used_in_pages > 0) {
                $usage['used_in_pages']++;
            } elseif ($item->used_in_posts > 0) {
                $usage['used_in_posts']++;
            } else {
                $usage['unused']++;
            }
        }
        
        return $usage;
    }

    public function searchMedia(Website $website, string $searchTerm): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('alt_text', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('file_name', 'LIKE', "%{$searchTerm}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function filterMedia(Website $website, array $filters): Collection
    {
        $query = $this->model->where('website_id', $website->id);
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['min_size'])) {
            $query->where('size', '>=', $filters['min_size']);
        }
        
        if (isset($filters['max_size'])) {
            $query->where('size', '<=', $filters['max_size']);
        }
        
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'largest':
                    $query->orderBy('size', 'desc');
                    break;
                case 'smallest':
                    $query->orderBy('size', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        }
        
        return $query->get();
    }

    public function updateAltText(Media $media, string $altText): Media
    {
        return $this->update($media, ['alt_text' => $altText]);
    }

    public function updateMediaName(Media $media, string $name): Media
    {
        return $this->update($media, ['name' => $name]);
    }

    public function updateMediaSettings(Media $media, array $settings): Media
    {
        $currentSettings = $media->settings ?? [];
        $newSettings = array_merge($currentSettings, $settings);
        return $this->update($media, ['settings' => $newSettings]);
    }

    public function getMediaByExtension(Website $website, string $extension): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('file_name', 'LIKE', "%.{$extension}")
            ->get();
    }

    public function getMediaSizeBreakdown(Website $website): array
    {
        $media = $this->model->where('website_id', $website->id)->get();
        
        return [
            'small' => $media->where('size', '<=', 1024 * 1024)->count(), // <= 1MB
            'medium' => $media->where('size', '>', 1024 * 1024)->where('size', '<=', 5 * 1024 * 1024)->count(), // 1-5MB
            'large' => $media->where('size', '>', 5 * 1024 * 1024)->where('size', '<=', 10 * 1024 * 1024)->count(), // 5-10MB
            'xlarge' => $media->where('size', '>', 10 * 1024 * 1024)->count(), // > 10MB
        ];
    }

    public function getMediaMimeTypes(Website $website): array
    {
        return $this->model->where('website_id', $website->id)
            ->groupBy('mime_type')
            ->selectRaw('mime_type, count(*) as count')
            ->get()
            ->pluck('count', 'mime_type')
            ->toArray();
    }

    public function cleanupUnusedMedia(Website $website, array $options = []): int
    {
        $media = $this->model->where('website_id', $website->id)
            ->where('used_in_pages', 0)
            ->where('used_in_posts', 0)
            ->get();
        
        $deletedCount = 0;
        
        foreach ($media as $item) {
            if ($this->deleteMedia($item)) {
                $deletedCount++;
            }
        }
        
        return $deletedCount;
    }

    public function syncMediaUsage(Website $website): void
    {
        // This would scan pages and posts to update usage counts
        // Implementation depends on your specific use case
    }

    public function generateMediaUrl(Media $media, ?string $size = null): string
    {
        if ($size && isset($media->thumbnails[$size])) {
            return Storage::disk('public')->url($media->thumbnails[$size]);
        }
        
        return Storage::disk('public')->url($media->path);
    }

    public function getMediaThumbnails(Media $media): array
    {
        if ($media->type !== 'image' || !$media->thumbnails) {
            return [];
        }
        
        $thumbnails = [];
        foreach ($media->thumbnails as $size => $path) {
            $thumbnails[$size] = Storage::disk('public')->url($path);
        }
        
        return $thumbnails;
    }

    public function deleteMediaFolder(Website $website, string $folder): bool
    {
        $media = $this->model->where('website_id', $website->id)
            ->where('path', 'LIKE', "websites/{$website->id}/media/{$folder}/%")
            ->get();
        
        foreach ($media as $item) {
            $this->deleteMedia($item);
        }
        
        Storage::disk('public')->deleteDirectory("websites/{$website->id}/media/{$folder}");
        
        return true;
    }

    public function getMediaByFolder(Website $website, string $folder): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('path', 'LIKE', "websites/{$website->id}/media/{$folder}/%")
            ->get();
    }

    public function getMediaDirectories(Website $website): array
    {
        $directories = Storage::disk('public')->directories("websites/{$website->id}/media");
        
        return array_map(function ($dir) {
            return basename($dir);
        }, $directories);
    }

    public function moveMedia(Media $media, string $newFolder): Media
    {
        $oldPath = $media->path;
        $newPath = "websites/{$media->website_id}/media/{$newFolder}/" . basename($oldPath);
        
        Storage::disk('public')->move($oldPath, $newPath);
        
        // Move thumbnails
        if ($media->thumbnails) {
            $newThumbnails = [];
            foreach ($media->thumbnails as $size => $path) {
                $newThumbPath = "websites/{$media->website_id}/media/{$newFolder}/" . basename($path);
                Storage::disk('public')->move($path, $newThumbPath);
                $newThumbnails[$size] = $newThumbPath;
            }
            $media->thumbnails = $newThumbnails;
        }
        
        $media->path = $newPath;
        $media->save();
        
        return $media->fresh();
    }
}