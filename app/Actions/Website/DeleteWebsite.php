<?php

namespace App\Actions\Website;

use App\Actions\BaseAction;
use App\Models\Website;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DeleteWebsite extends BaseAction
{
    protected array $validationRules = [
        'website_id' => 'required|exists:websites,id',
        'force' => 'nullable|boolean',
        'delete_media' => 'nullable|boolean',
    ];

    public function execute(array $data): bool
    {
        $this->data = $data;
        $this->validate($data);

        $this->beginTransaction();

        try {
            $website = Website::findOrFail($data['website_id']);
            $force = $data['force'] ?? false;
            $deleteMedia = $data['delete_media'] ?? true;

            // Validate deletion
            $this->validateDeletion($website);

            // Delete associated data
            $website->pages()->delete();
            $website->menus()->delete();
            $website->forms()->delete();
            $website->analytics()->delete();
            $website->seo()->delete();
            $website->domains()->delete();
            
            // Delete media and files
            if ($deleteMedia) {
                $this->deleteMediaFiles($website);
                $website->media()->delete();
            }

            // Delete website
            if ($force) {
                $website->forceDelete();
            } else {
                $website->delete();
            }

            // Clear cache
            Cache::forget('website_' . $website->id);
            Cache::forget('user_websites_' . $website->user_id);
            Cache::forget('website_domain_' . $website->domain);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\WebsiteDeleted', [
                'website' => $website,
                'force' => $force,
            ]);

            return true;

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to delete website', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function validateDeletion(Website $website): void
    {
        // Check if website can be deleted
        if ($website->status === 'published' && !$this->data['force'] ?? false) {
            throw new ActionException(
                'Cannot delete a published website. Unpublish it first or use force delete.',
                ['website_id' => $website->id, 'status' => $website->status]
            );
        }

        // Check if website has active subscriptions
        if ($website->subscriptions()->where('status', 'active')->exists()) {
            throw new ActionException(
                'Cannot delete website with active subscriptions.',
                ['website_id' => $website->id]
            );
        }
    }

    protected function deleteMediaFiles(Website $website): void
    {
        $path = "websites/{$website->id}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->deleteDirectory($path);
        }
    }
}