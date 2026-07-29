<?php

namespace App\Actions\Website;

use App\Actions\BaseAction;
use App\Models\Website;
use App\Models\User;
use App\Services\TemplateService;
use App\Services\DomainService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CreateWebsite extends BaseAction
{
    protected array $validationRules = [
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'domain' => 'nullable|string|max:255|unique:websites,domain',
        'theme' => 'nullable|string|exists:templates,slug',
        'template' => 'nullable|string|exists:templates,slug',
        'is_public' => 'nullable|boolean',
        'settings' => 'nullable|array',
        'seo_settings' => 'nullable|array',
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
        $this->validate($data);

        $this->beginTransaction();

        try {
            $user = User::findOrFail($data['user_id']);
            
            // Check subscription limits
            $this->validateWebsiteLimit($user);

            // Create website
            $website = Website::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug($data['name']),
                'description' => $data['description'] ?? null,
                'domain' => $data['domain'] ?? null,
                'status' => 'draft',
                'is_public' => $data['is_public'] ?? false,
                'theme' => $data['theme'] ?? 'default',
                'settings' => array_merge([
                    'timezone' => 'UTC',
                    'date_format' => 'Y-m-d',
                    'time_format' => 'H:i',
                    'language' => 'en',
                    'currency' => 'USD',
                ], $data['settings'] ?? []),
                'seo_settings' => array_merge([
                    'meta_title' => $data['name'],
                    'meta_description' => $data['description'] ?? '',
                ], $data['seo_settings'] ?? []),
            ]);

            // Assign domain if provided
            if ($data['domain'] ?? false) {
                $this->assignDomain($website, $data['domain']);
            }

            // Apply template if provided
            if ($data['template'] ?? false) {
                $this->templateService->applyTemplate($website, $data['template']);
            }

            // Create default pages
            $this->createDefaultPages($website);

            // Create default menus
            $this->createDefaultMenus($website);

            // Clear cache
            Cache::forget('user_websites_' . $user->id);

            $this->commit();

            // Dispatch event
            $this->dispatchEvent('App\Events\WebsiteCreated', [
                'website' => $website,
                'user' => $user,
            ]);

            return $website->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to create website', ['error' => $e->getMessage()]);
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
                "You have reached the maximum number of websites allowed for your plan.",
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

    protected function createDefaultPages(Website $website): void
    {
        $pages = [
            [
                'title' => 'Home',
                'content' => '<h1>Welcome to ' . $website->name . '</h1><p>This is your new website. Start customizing it today!</p>',
                'is_home' => true,
                'status' => 'published',
                'order' => 0,
            ],
            [
                'title' => 'About',
                'content' => '<h2>About Us</h2><p>Learn more about our company and what we do.</p>',
                'status' => 'published',
                'order' => 1,
            ],
            [
                'title' => 'Contact',
                'content' => '<h2>Contact Us</h2><p>Get in touch with us.</p>',
                'status' => 'published',
                'order' => 2,
            ],
        ];

        foreach ($pages as $pageData) {
            $website->pages()->create($pageData);
        }
    }

    protected function createDefaultMenus(Website $website): void
    {
        $menus = [
            [
                'name' => 'Primary Navigation',
                'location' => 'primary',
                'items' => [
                    ['label' => 'Home', 'url' => '/', 'order' => 0],
                    ['label' => 'About', 'url' => '/about', 'order' => 1],
                    ['label' => 'Contact', 'url' => '/contact', 'order' => 2],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Footer Navigation',
                'location' => 'footer',
                'items' => [
                    ['label' => 'Privacy Policy', 'url' => '/privacy', 'order' => 0],
                    ['label' => 'Terms of Service', 'url' => '/terms', 'order' => 1],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($menus as $menuData) {
            $website->menus()->create($menuData);
        }
    }
}