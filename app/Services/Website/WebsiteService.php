<?php

namespace App\Services\Website;

use App\Models\Website;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class WebsiteService
{
    public function createWebsite(array $data, User $user): Website
    {
        $website = Website::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'domain' => $data['domain'] ?? null,
            'status' => 'active',
            'settings' => $data['settings'] ?? [],
            'theme' => $data['theme'] ?? 'default',
            'is_public' => $data['is_public'] ?? true,
        ]);

        Cache::forget('user_websites_' . $user->id);
        return $website;
    }

    public function updateWebsite(Website $website, array $data): Website
    {
        $website->update($data);
        Cache::forget('website_' . $website->id);
        Cache::forget('user_websites_' . $website->user_id);
        return $website;
    }

    public function deleteWebsite(Website $website): bool
    {
        Cache::forget('website_' . $website->id);
        Cache::forget('user_websites_' . $website->user_id);
        return $website->delete();
    }

    public function getWebsiteByDomain(string $domain): ?Website
    {
        return Cache::remember('website_domain_' . $domain, 3600, function () use ($domain) {
            return Website::where('domain', $domain)->first();
        });
    }

    public function getUserWebsites(User $user)
    {
        return Cache::remember('user_websites_' . $user->id, 3600, function () use ($user) {
            return $user->websites()->where('status', 'active')->get();
        });
    }

    public function publishWebsite(Website $website): bool
    {
        $website->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        Cache::forget('website_' . $website->id);
        return true;
    }

    public function duplicateWebsite(Website $website, array $data): Website
    {
        $newWebsite = $website->replicate();
        $newWebsite->name = $data['name'] ?? $website->name . ' (Copy)';
        $newWebsite->slug = Str::slug($newWebsite->name);
        $newWebsite->domain = $data['domain'] ?? null;
        $newWebsite->status = 'draft';
        $newWebsite->save();
        
        return $newWebsite;
    }
}