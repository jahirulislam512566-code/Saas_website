<?php

namespace App\Actions\Website;

use App\Actions\BaseAction;
use App\Models\Website;
use App\Services\TemplateService;
use App\Services\DomainService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class UpdateWebsite extends BaseAction
{
    protected array $validationRules = [
        'website_id' => 'required|exists:websites,id',
        'name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'domain' => 'nullable|string|max:255|unique:websites,domain,{website_id}',
        'theme' => 'nullable|string|exists:templates,slug',
        'is_public' => 'nullable|boolean',
        'settings' => 'nullable|array',
        'seo_settings' => 'nullable|array',
        'status' => 'nullable|in:draft,published,archived',
    ];

    protected TemplateService $templateService;
    protected DomainService $domainService;

    public function __construct(
        TemplateService $templateService,
        DomainService $domainService
    ) {
        $this->templateService = $templateService;
        $this->domainService = $domainService;
    }

    public function execute(array $data): Website
    {
        $this->data = $data;
        
        // Dynamic validation rule for domain uniqueness
        if (isset($data['website_id'])) {
            $this->validationRules['domain'] = str_replace(
                '{website_id}',
                $data['website_id'],
                $this->validationRules['domain']
            );
        }
        
        $this->validate($data);

        $this->beginTransaction();

        try {
            $website = Website::findOrFail($data['website_id']);
            $originalData = $website->toArray();

            // Prepare update data
            $updateData = [];
            
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
                if ($data['name'] !== $website->name) {
                    $updateData['slug'] = $this->generateUniqueSlug($data['name'], $website->id);
                }
            }
            
            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }
            
            if (isset($data['is_public'])) {
                $updateData['is_public'] = $data['is_public'];
            }
            
            if (isset($data['status'])) {
                $updateData['status'] = $data['status'];
                if ($data['status'] === 'published') {
                    $updateData['published_at'] = now();
                }
            }
            
            if (isset($data['settings'])) {
                $updateData['settings'] = array_merge(
                    $website->settings ?? [],
                    $data['settings']
                );
            }
            
            if (isset($data['seo_settings'])) {
                $updateData['seo_settings'] = array_merge(
                    $website->seo_settings ?? [],
                    $data['seo_settings']
                );
            }

            // Update theme if provided
            if (isset($data['theme']) && $data['theme'] !== $website->theme) {
                $updateData['theme'] = $data['theme'];
                $this->templateService->applyTemplate($website, $data['theme']);
            }

            // Update domain if provided
            if (isset($data['domain']) && $data['domain'] !== $website->domain) {
                if ($data['domain'] === null) {
                    $this->domainService->removeDomain($website);
                } else {
                    $this->assignDomain($website, $data['domain']);
                }
                $updateData['domain'] = $data['domain'];
            }

            // Perform update
            $website->update($updateData);

            // Clear cache
            Cache::forget('website_' . $website->id);
            Cache::forget('user_websites_' . $website->user_id);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\WebsiteUpdated', [
                'website' => $website,
                'original_data' => $originalData,
                'updated_data' => $updateData,
            ]);

            return $website->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to update website', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function generateUniqueSlug(string $name, int $excludeId): string
    {
        $slug = Str::slug($name);
        $count = 2;
        
        while (Website::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = Str::slug($name) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    protected function assignDomain(Website $website, string $domain): void
    {
        if ($this->domainService->checkDomainAvailability($domain)['available'] ?? false) {
            $this->domainService->registerDomain([
                'website_id' => $website->id,
                'domain' => $domain,
                'years' => 1,
                'auto_renew' => true,
            ]);
        } else {
            throw new ActionException('Domain is not available', ['domain' => $domain]);
        }
    }
}