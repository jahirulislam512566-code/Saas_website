<?php

namespace App\Actions\Website;

use App\Actions\BaseAction;
use App\Models\Website;
use App\Models\User;
use App\Services\TemplateService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CloneWebsite extends BaseAction
{
    protected array $validationRules = [
        'source_website_id' => 'required|exists:websites,id',
        'user_id' => 'nullable|exists:users,id',
        'name' => 'nullable|string|max:255',
        'domain' => 'nullable|string|max:255|unique:websites,domain',
        'clone_media' => 'nullable|boolean',
        'clone_templates' => 'nullable|boolean',
        'clone_pages' => 'nullable|boolean',
        'clone_menus' => 'nullable|boolean',
        'clone_forms' => 'nullable|boolean',
        'clone_settings' => 'nullable|boolean',
    ];

    protected TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function execute(array $data): Website
    {
        $this->data = $data;
        $this->validate($data);

        $this->beginTransaction();

        try {
            $sourceWebsite = Website::findOrFail($data['source_website_id']);
            $targetUserId = $data['user_id'] ?? $sourceWebsite->user_id;
            $targetUser = User::findOrFail($targetUserId);

            // Check subscription limits
            $this->validateWebsiteLimit($targetUser);

            // Create new website
            $newWebsite = Website::create([
                'user_id' => $targetUserId,
                'name' => $data['name'] ?? $sourceWebsite->name . ' (Copy)',
                'slug' => $this->generateUniqueSlug($data['name'] ?? $sourceWebsite->name . ' (Copy)'),
                'description' => $sourceWebsite->description,
                'domain' => $data['domain'] ?? null,
                'status' => 'draft',
                'is_public' => false,
                'theme' => $sourceWebsite->theme,
                'settings' => ($data['clone_settings'] ?? true) ? $sourceWebsite->settings : [],
                'seo_settings' => ($data['clone_settings'] ?? true) ? $sourceWebsite->seo_settings : [],
            ]);

            // Clone pages
            if ($data['clone_pages'] ?? true) {
                $this->clonePages($sourceWebsite, $newWebsite);
            }

            // Clone menus
            if ($data['clone_menus'] ?? true) {
                $this->cloneMenus($sourceWebsite, $newWebsite);
            }

            // Clone forms
            if ($data['clone_forms'] ?? true) {
                $this->cloneForms($sourceWebsite, $newWebsite);
            }

            // Clone media
            if ($data['clone_media'] ?? true) {
                $this->cloneMedia($sourceWebsite, $newWebsite);
            }

            // Apply template
            if ($data['clone_templates'] ?? true) {
                $this->templateService->applyTemplate($newWebsite, $sourceWebsite->theme);
            }

            // Clear cache
            Cache::forget('user_websites_' . $targetUserId);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\WebsiteCloned', [
                'source_website' => $sourceWebsite,
                'new_website' => $newWebsite,
                'user' => $targetUser,
            ]);

            return $newWebsite->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to clone website', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function validateWebsiteLimit(User $user): void
    {
        $subscription = $user->subscriptions()->where('status', 'active')->first();
        $maxWebsites = $subscription?->plan->max_websites ?? 1;
        $currentWebsites = $user->websites()->count();
        
        if ($currentWebsites >= $maxWebsites) {
            throw new ActionException(
                "User has reached the maximum number of websites allowed.",
                ['limit' => $maxWebsites, 'current' => $currentWebsites]
            );
        }
    }

    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = 2;
        
        while (Website::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    protected function clonePages(Website $source, Website $target): void
    {
        foreach ($source->pages as $page) {
            $newPage = $page->replicate();
            $newPage->website_id = $target->id;
            $newPage->slug = $this->generateUniquePageSlug($target, $page->title);
            $newPage->is_home = false;
            $newPage->save();

            // Clone sections
            foreach ($page->sections as $section) {
                $newSection = $section->replicate();
                $newSection->page_id = $newPage->id;
                $newSection->save();
            }
        }

        // Set first page as home
        $firstPage = $target->pages()->first();
        if ($firstPage) {
            $firstPage->update(['is_home' => true]);
        }
    }

    protected function cloneMenus(Website $source, Website $target): void
    {
        foreach ($source->menus as $menu) {
            $newMenu = $menu->replicate();
            $newMenu->website_id = $target->id;
            $newMenu->save();
        }
    }

    protected function cloneForms(Website $source, Website $target): void
    {
        foreach ($source->forms as $form) {
            $newForm = $form->replicate();
            $newForm->website_id = $target->id;
            $newForm->slug = $this->generateUniqueFormSlug($target, $form->name);
            $newForm->save();
        }
    }

    protected function cloneMedia(Website $source, Website $target): void
    {
        foreach ($source->media as $media) {
            // Copy file
            $newPath = str_replace(
                "websites/{$source->id}",
                "websites/{$target->id}",
                $media->path
            );
            
            Storage::disk('public')->copy($media->path, $newPath);
            
            // Create media record
            $newMedia = $media->replicate();
            $newMedia->website_id = $target->id;
            $newMedia->path = $newPath;
            
            // Copy thumbnails
            if ($media->thumbnails) {
                $newThumbnails = [];
                foreach ($media->thumbnails as $size => $path) {
                    $newThumbPath = str_replace(
                        "websites/{$source->id}",
                        "websites/{$target->id}",
                        $path
                    );
                    Storage::disk('public')->copy($path, $newThumbPath);
                    $newThumbnails[$size] = $newThumbPath;
                }
                $newMedia->thumbnails = $newThumbnails;
            }
            
            $newMedia->save();
        }
    }

    protected function generateUniquePageSlug(Website $website, string $title): string
    {
        $slug = Str::slug($title);
        $count = 2;
        
        while ($website->pages()->where('slug', $slug)->exists()) {
            $slug = Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    protected function generateUniqueFormSlug(Website $website, string $name): string
    {
        $slug = Str::slug($name);
        $count = 2;
        
        while ($website->forms()->where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
}