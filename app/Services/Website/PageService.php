<?php

namespace App\Services\Website;

use App\Models\Page;
use App\Models\Website;
use Illuminate\Support\Str;

class PageService
{
    public function createPage(Website $website, array $data): Page
    {
        $page = Page::create([
            'website_id' => $website->id,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'content' => $data['content'] ?? '',
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'is_home' => $data['is_home'] ?? false,
            'order' => $data['order'] ?? 0,
            'template' => $data['template'] ?? 'default',
            'settings' => $data['settings'] ?? [],
        ]);

        if ($page->is_home) {
            Page::where('website_id', $website->id)
                ->where('id', '!=', $page->id)
                ->update(['is_home' => false]);
        }

        return $page;
    }

    public function updatePage(Page $page, array $data): Page
    {
        $page->update($data);
        
        if (isset($data['is_home']) && $data['is_home']) {
            Page::where('website_id', $page->website_id)
                ->where('id', '!=', $page->id)
                ->update(['is_home' => false]);
        }
        
        return $page;
    }

    public function deletePage(Page $page): bool
    {
        return $page->delete();
    }

    public function getWebsitePages(Website $website)
    {
        return $website->pages()->orderBy('order')->get();
    }

    public function getPublishedPages(Website $website)
    {
        return $website->pages()->where('status', 'published')->orderBy('order')->get();
    }

    public function getPageBySlug(Website $website, string $slug): ?Page
    {
        return $website->pages()->where('slug', $slug)->first();
    }

    public function getHomePage(Website $website): ?Page
    {
        return $website->pages()->where('is_home', true)->first();
    }

    public function reorderPages(array $order): bool
    {
        foreach ($order as $id => $position) {
            Page::where('id', $id)->update(['order' => $position]);
        }
        return true;
    }

    public function duplicatePage(Page $page): Page
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Str::slug($newPage->title);
        $newPage->status = 'draft';
        $newPage->save();
        return $newPage;
    }
}