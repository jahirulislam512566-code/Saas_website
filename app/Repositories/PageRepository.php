<?php

namespace App\Repositories;

use App\Models\Page;
use App\Models\Website;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PageRepository extends BaseRepository
{
    public function __construct(Page $page)
    {
        parent::__construct($page);
    }

    public function findBySlug(Website $website, string $slug): ?Page
    {
        return $this->model->where('website_id', $website->id)
            ->where('slug', $slug)
            ->first();
    }

    public function findByWebsite(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->orderBy('order')
            ->get();
    }

    public function findByWebsitePaginated(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('website_id', $website->id)
            ->orderBy('order')
            ->paginate($perPage);
    }

    public function getPublishedPages(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'published')
            ->orderBy('order')
            ->get();
    }

    public function getDraftPages(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('status', 'draft')
            ->orderBy('order')
            ->get();
    }

    public function getHomePage(Website $website): ?Page
    {
        return $this->model->where('website_id', $website->id)
            ->where('is_home', true)
            ->first();
    }

    public function createPage(Website $website, array $data): Page
    {
        $data['website_id'] = $website->id;
        $data['slug'] = $this->generateUniqueSlug($website, $data['title']);
        
        $page = $this->create($data);
        
        if ($page->is_home) {
            $this->removeOtherHomePages($website, $page->id);
        }
        
        return $page;
    }

    public function updatePage(Page $page, array $data): Page
    {
        if (isset($data['title']) && $data['title'] !== $page->title) {
            $data['slug'] = $this->generateUniqueSlug($page->website, $data['title']);
        }
        
        $page = $this->update($page, $data);
        
        if ($page->is_home) {
            $this->removeOtherHomePages($page->website, $page->id);
        }
        
        return $page;
    }

    public function deletePage(Page $page): bool
    {
        // Delete sections
        $page->sections()->delete();
        
        return $this->delete($page);
    }

    public function publishPage(Page $page): Page
    {
        return $this->update($page, [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublishPage(Page $page): Page
    {
        return $this->update($page, [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function setHomePage(Website $website, Page $page): Page
    {
        $this->removeOtherHomePages($website);
        return $this->update($page, ['is_home' => true]);
    }

    public function removeOtherHomePages(Website $website, ?int $excludeId = null): void
    {
        $query = $this->model->where('website_id', $website->id)->where('is_home', true);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $query->update(['is_home' => false]);
    }

    public function reorderPages(array $order): bool
    {
        foreach ($order as $id => $position) {
            $this->model->where('id', $id)->update(['order' => $position]);
        }
        return true;
    }

    public function duplicatePage(Page $page): Page
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = $this->generateUniqueSlug($page->website, $newPage->title);
        $newPage->status = 'draft';
        $newPage->published_at = null;
        $newPage->is_home = false;
        $newPage->save();
        
        // Duplicate sections
        foreach ($page->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $newPage->id;
            $newSection->save();
        }
        
        return $newPage->fresh();
    }

    public function searchPages(Website $website, string $searchTerm): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('meta_title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('meta_description', 'LIKE', "%{$searchTerm}%");
            })
            ->get();
    }

    public function getPagesByTemplate(Website $website, string $template): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->where('template', $template)
            ->get();
    }

    public function getPageStatistics(Website $website): array
    {
        return [
            'total' => $this->model->where('website_id', $website->id)->count(),
            'published' => $this->model->where('website_id', $website->id)->where('status', 'published')->count(),
            'draft' => $this->model->where('website_id', $website->id)->where('status', 'draft')->count(),
            'has_home' => $this->model->where('website_id', $website->id)->where('is_home', true)->exists(),
            'templates' => $this->model->where('website_id', $website->id)
                ->groupBy('template')
                ->selectRaw('template, count(*) as count')
                ->get(),
        ];
    }

    public function updatePageSettings(Page $page, array $settings): Page
    {
        $currentSettings = $page->settings ?? [];
        $newSettings = array_merge($currentSettings, $settings);
        return $this->update($page, ['settings' => $newSettings]);
    }

    public function togglePageVisibility(Page $page): Page
    {
        $page->is_visible = !$page->is_visible;
        $page->save();
        return $page->fresh();
    }

    public function getPageAncestors(Page $page): Collection
    {
        $ancestors = collect();
        $current = $page;
        
        while ($current->parent_id) {
            $parent = $this->find($current->parent_id);
            if ($parent) {
                $ancestors->prepend($parent);
                $current = $parent;
            } else {
                break;
            }
        }
        
        return $ancestors;
    }

    public function getPageChildren(Page $page): Collection
    {
        return $this->model->where('parent_id', $page->id)->orderBy('order')->get();
    }

    public function getPageTree(Website $website): Collection
    {
        return $this->model->where('website_id', $website->id)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();
    }

    public function movePage(Page $page, ?int $parentId, int $position): Page
    {
        $page->update([
            'parent_id' => $parentId,
            'order' => $position,
        ]);
        
        return $page->fresh();
    }

    private function generateUniqueSlug(Website $website, string $title): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $count = 2;
        
        while ($this->model->where('website_id', $website->id)->where('slug', $slug)->exists()) {
            $slug = \Illuminate\Support\Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
}