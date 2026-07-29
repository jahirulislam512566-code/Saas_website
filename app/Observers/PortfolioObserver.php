<?php

namespace App\Observers;

use App\Models\Portfolio;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PortfolioObserver extends BaseObserver
{
    /**
     * Handle the Portfolio "creating" event.
     */
    public function creating(Portfolio $portfolio): void
    {
        // Generate slug
        if (empty($portfolio->slug)) {
            $portfolio->slug = $this->generateSlug($portfolio->title);
        }

        // Set default status
        if (empty($portfolio->is_active)) {
            $portfolio->is_active = true;
        }

        $this->log('creating', $portfolio);
    }

    /**
     * Handle the Portfolio "created" event.
     */
    public function created(Portfolio $portfolio): void
    {
        Cache::forget('portfolio_count');
        Cache::forget('portfolio_list');
        Cache::forget('portfolio_featured');
        
        $this->createAuditLog($portfolio, 'created');

        $this->log('created', $portfolio);
    }

    /**
     * Handle the Portfolio "updating" event.
     */
    public function updating(Portfolio $portfolio): void
    {
        // Track slug changes
        if ($portfolio->isDirty('title')) {
            $portfolio->slug = $this->generateSlug($portfolio->title);
        }

        // Track featured status changes
        if ($portfolio->isDirty('is_featured')) {
            $oldFeatured = $portfolio->getOriginal('is_featured');
            $newFeatured = $portfolio->is_featured;
            
            $this->logFeaturedChange($portfolio, $oldFeatured, $newFeatured);
        }

        $this->log('updating', $portfolio);
    }

    /**
     * Handle the Portfolio "updated" event.
     */
    public function updated(Portfolio $portfolio): void
    {
        Cache::forget("portfolio_{$portfolio->id}");
        Cache::forget("portfolio_{$portfolio->slug}");
        Cache::forget('portfolio_list');
        Cache::forget('portfolio_featured');
        
        $this->createAuditLog($portfolio, 'updated', $portfolio->getChanges());

        $this->log('updated', $portfolio);
    }

    /**
     * Handle the Portfolio "deleted" event.
     */
    public function deleted(Portfolio $portfolio): void
    {
        Cache::forget("portfolio_{$portfolio->id}");
        Cache::forget('portfolio_count');
        Cache::forget('portfolio_list');

        $this->log('deleted', $portfolio);
    }

    /**
     * Handle the Portfolio "restored" event.
     */
    public function restored(Portfolio $portfolio): void
    {
        Cache::forget("portfolio_{$portfolio->id}");
        Cache::forget('portfolio_list');

        $this->log('restored', $portfolio);
    }

    /**
     * Handle the Portfolio "forceDeleted" event.
     */
    public function forceDeleted(Portfolio $portfolio): void
    {
        Cache::forget("portfolio_{$portfolio->id}");
        Cache::forget('portfolio_count');
        Cache::forget('portfolio_list');

        Log::warning('Portfolio permanently deleted', [
            'portfolio_id' => $portfolio->id,
            'title' => $portfolio->title,
            'deleted_by' => $this->getUserId(),
        ]);
    }

    /**
     * Generate unique slug.
     */
    protected function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Portfolio::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Log featured change.
     */
    protected function logFeaturedChange(Portfolio $portfolio, bool $oldFeatured, bool $newFeatured): void
    {
        Log::info('Portfolio featured status changed', [
            'portfolio_id' => $portfolio->id,
            'title' => $portfolio->title,
            'old_featured' => $oldFeatured,
            'new_featured' => $newFeatured,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Portfolio $portfolio, string $action, array $changes = []): void
    {
        // Create audit log entry if you have an AuditLog model
        // \App\Models\AuditLog::create([
        //     'model_type' => get_class($portfolio),
        //     'model_id' => $portfolio->id,
        //     'action' => $action,
        //     'changes' => $changes,
        //     'user_id' => $this->getUserId(),
        //     'ip_address' => $this->getIpAddress(),
        // ]);
    }
}