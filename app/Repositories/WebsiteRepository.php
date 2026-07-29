<?php

namespace App\Repositories;

use App\Models\Website;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class WebsiteRepository extends BaseRepository
{
    public function __construct(Website $website)
    {
        parent::__construct($website);
    }

    public function findByDomain(string $domain): ?Website
    {
        return $this->model->where('domain', $domain)->first();
    }

    public function findBySlug(string $slug): ?Website
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findByUser(User $user): Collection
    {
        return $this->model->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    }

    public function findByUserPaginated(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getPublishedWebsites(): Collection
    {
        return $this->model->where('status', 'published')
            ->where('is_public', true)
            ->get();
    }

    public function getActiveWebsites(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getWebsitesByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function createWebsite(User $user, array $data): Website
    {
        $data['user_id'] = $user->id;
        return $this->create($data);
    }

    public function updateWebsite(Website $website, array $data): Website
    {
        return $this->update($website, $data);
    }

    public function publishWebsite(Website $website): Website
    {
        return $this->update($website, [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublishWebsite(Website $website): Website
    {
        return $this->update($website, [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function archiveWebsite(Website $website): Website
    {
        return $this->update($website, ['status' => 'archived']);
    }

    public function deleteWebsite(Website $website): bool
    {
        // Begin transaction
        $this->beginTransaction();
        
        try {
            // Delete related data
            $website->pages()->delete();
            $website->menus()->delete();
            $website->media()->delete();
            $website->forms()->delete();
            $website->analytics()->delete();
            
            // Delete website
            $website->delete();
            
            $this->commitTransaction();
            return true;
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    public function getWebsiteAnalytics(Website $website): array
    {
        return [
            'total_pages' => $website->pages()->count(),
            'published_pages' => $website->pages()->where('status', 'published')->count(),
            'total_media' => $website->media()->count(),
            'total_forms' => $website->forms()->count(),
            'total_submissions' => $website->formSubmissions()->count(),
            'total_analytics' => $website->analytics()->count(),
            'unique_visitors' => $website->analytics()->distinct('session_id')->count(),
        ];
    }

    public function getWebsiteStats(Website $website): array
    {
        $analytics = $website->analytics()->where('timestamp', '>=', now()->subDays(30))->get();
        
        return [
            'visits' => $analytics->count(),
            'unique_visitors' => $analytics->unique('session_id')->count(),
            'page_views' => $analytics->count(),
            'bounce_rate' => $this->calculateBounceRate($analytics),
            'avg_time_on_site' => $this->calculateAvgTimeOnSite($analytics),
        ];
    }

    public function searchWebsites(string $searchTerm): Collection
    {
        return $this->model->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('domain', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        })->get();
    }

    public function getWebsitesByTemplate(string $template): Collection
    {
        return $this->model->where('theme', $template)->get();
    }

    public function getExpiringWebsites(int $days = 7): Collection
    {
        return $this->model->where('status', 'published')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', now()->addDays($days))
            ->get();
    }

    public function getExpiredWebsites(): Collection
    {
        return $this->model->where('status', 'published')
            ->where('expires_at', '<', now())
            ->get();
    }

    public function duplicateWebsite(Website $website, User $user): Website
    {
        $newWebsite = $website->replicate();
        $newWebsite->user_id = $user->id;
        $newWebsite->name = $website->name . ' (Copy)';
        $newWebsite->slug = $this->generateUniqueSlug($newWebsite->name);
        $newWebsite->status = 'draft';
        $newWebsite->published_at = null;
        $newWebsite->save();

        // Duplicate pages
        foreach ($website->pages as $page) {
            $newPage = $page->replicate();
            $newPage->website_id = $newWebsite->id;
            $newPage->slug = $this->generateUniquePageSlug($newWebsite, $page->title);
            $newPage->save();
            
            // Duplicate sections
            foreach ($page->sections as $section) {
                $newSection = $section->replicate();
                $newSection->page_id = $newPage->id;
                $newSection->save();
            }
        }

        // Duplicate menus
        foreach ($website->menus as $menu) {
            $newMenu = $menu->replicate();
            $newMenu->website_id = $newWebsite->id;
            $newMenu->save();
        }

        // Duplicate forms
        foreach ($website->forms as $form) {
            $newForm = $form->replicate();
            $newForm->website_id = $newWebsite->id;
            $newForm->slug = $this->generateUniqueFormSlug($newWebsite, $form->name);
            $newForm->save();
        }

        return $newWebsite->fresh();
    }

    public function assignDomain(Website $website, string $domain): Website
    {
        return $this->update($website, ['domain' => $domain]);
    }

    public function removeDomain(Website $website): Website
    {
        return $this->update($website, ['domain' => null]);
    }

    public function updateSettings(Website $website, array $settings): Website
    {
        $currentSettings = $website->settings ?? [];
        $newSettings = array_merge($currentSettings, $settings);
        return $this->update($website, ['settings' => $newSettings]);
    }

    public function togglePublic(Website $website): Website
    {
        return $this->update($website, ['is_public' => !$website->is_public]);
    }

    public function updateTheme(Website $website, string $theme): Website
    {
        return $this->update($website, ['theme' => $theme]);
    }

    public function getWebsiteMetrics(Website $website): array
    {
        return [
            'pages' => $website->pages()->count(),
            'published_pages' => $website->pages()->where('status', 'published')->count(),
            'media' => $website->media()->count(),
            'media_size' => $website->media()->sum('size'),
            'forms' => $website->forms()->count(),
            'submissions' => $website->formSubmissions()->count(),
            'analytics' => $website->analytics()->count(),
            'menus' => $website->menus()->count(),
            'domains' => $website->domains()->count(),
        ];
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = 2;
        
        while ($this->model->where('slug', $slug)->exists()) {
            $slug = \Illuminate\Support\Str::slug($name) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    private function generateUniquePageSlug(Website $website, string $title): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $count = 2;
        
        while ($website->pages()->where('slug', $slug)->exists()) {
            $slug = \Illuminate\Support\Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    private function generateUniqueFormSlug(Website $website, string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = 2;
        
        while ($website->forms()->where('slug', $slug)->exists()) {
            $slug = \Illuminate\Support\Str::slug($name) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    private function calculateBounceRate($analytics): float
    {
        $sessions = $analytics->groupBy('session_id');
        $bounces = $sessions->filter(function ($session) {
            return $session->count() === 1;
        });
        
        return $sessions->count() > 0 
            ? round(($bounces->count() / $sessions->count()) * 100, 2)
            : 0;
    }

    private function calculateAvgTimeOnSite($analytics): float
    {
        // Simplified calculation - in production, you'd track time on site properly
        return 0;
    }
}