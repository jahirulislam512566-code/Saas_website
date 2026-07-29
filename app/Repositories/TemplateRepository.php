<?php

namespace App\Repositories;

use App\Models\Template;
use App\Models\Website;
use Illuminate\Database\Eloquent\Collection;

class TemplateRepository extends BaseRepository
{
    public function __construct(Template $template)
    {
        parent::__construct($template);
    }

    public function findByName(string $name): ?Template
    {
        return $this->model->where('name', $name)->first();
    }

    public function getActiveTemplates(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getTemplatesByCategory(string $category): Collection
    {
        return $this->model->where('category', $category)->where('is_active', true)->get();
    }

    public function getPopularTemplates(int $limit = 10): Collection
    {
        return $this->model->where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getFreeTemplates(): Collection
    {
        return $this->model->where('is_free', true)->where('is_active', true)->get();
    }

    public function getPremiumTemplates(): Collection
    {
        return $this->model->where('is_free', false)->where('is_active', true)->get();
    }

    public function createTemplate(array $data): Template
    {
        return $this->create($data);
    }

    public function updateTemplate(Template $template, array $data): Template
    {
        return $this->update($template, $data);
    }

    public function deleteTemplate(Template $template): bool
    {
        return $this->delete($template);
    }

    public function incrementUsage(Template $template): Template
    {
        $template->increment('usage_count');
        return $template->fresh();
    }

    public function getTemplatesByWebsite(Website $website): Collection
    {
        return $this->model->where('id', $website->theme)->get();
    }

    public function getTemplateCategories(): array
    {
        return [
            'business' => 'Business',
            'portfolio' => 'Portfolio',
            'ecommerce' => 'E-commerce',
            'blog' => 'Blog',
            'landing' => 'Landing Page',
            'agency' => 'Agency',
            'restaurant' => 'Restaurant',
            'education' => 'Education',
            'health' => 'Health & Wellness',
            'technology' => 'Technology',
            'creative' => 'Creative',
            'personal' => 'Personal',
        ];
    }

    public function getTemplateStyles(): array
    {
        return [
            'modern' => 'Modern',
            'classic' => 'Classic',
            'minimal' => 'Minimal',
            'bold' => 'Bold',
            'elegant' => 'Elegant',
            'playful' => 'Playful',
            'professional' => 'Professional',
            'creative' => 'Creative',
            'corporate' => 'Corporate',
            'startup' => 'Startup',
        ];
    }

    public function getTemplateFeatures(Template $template): array
    {
        $features = [];
        
        if ($template->has_hero) $features[] = 'Hero Section';
        if ($template->has_about) $features[] = 'About Section';
        if ($template->has_services) $features[] = 'Services Section';
        if ($template->has_portfolio) $features[] = 'Portfolio Section';
        if ($template->has_team) $features[] = 'Team Section';
        if ($template->has_testimonials) $features[] = 'Testimonials Section';
        if ($template->has_blog) $features[] = 'Blog Section';
        if ($template->has_contact) $features[] = 'Contact Section';
        if ($template->has_pricing) $features[] = 'Pricing Section';
        if ($template->has_faq) $features[] = 'FAQ Section';
        if ($template->has_newsletter) $features[] = 'Newsletter Section';
        if ($template->has_gallery) $features[] = 'Gallery Section';
        if ($template->has_cta) $features[] = 'Call to Action Section';
        
        return $features;
    }

    public function searchTemplates(string $searchTerm): Collection
    {
        return $this->model->where('is_active', true)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('tags', 'LIKE', "%{$searchTerm}%");
            })
            ->get();
    }

    public function filterTemplates(array $filters): Collection
    {
        $query = $this->model->where('is_active', true);
        
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['style'])) {
            $query->where('style', $filters['style']);
        }
        
        if (isset($filters['price_type'])) {
            if ($filters['price_type'] === 'free') {
                $query->where('is_free', true);
            } elseif ($filters['price_type'] === 'premium') {
                $query->where('is_free', false);
            }
        }
        
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'popular':
                    $query->orderBy('usage_count', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        }
        
        return $query->get();
    }

    public function getRecommendedTemplates(int $limit = 5): Collection
    {
        return $this->model->where('is_active', true)
            ->where('is_recommended', true)
            ->limit($limit)
            ->get();
    }

    public function getNewTemplates(int $limit = 5): Collection
    {
        return $this->model->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTemplateStatistics(): array
    {
        return [
            'total' => $this->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'free' => $this->model->where('is_free', true)->count(),
            'premium' => $this->model->where('is_free', false)->count(),
            'total_usage' => $this->model->sum('usage_count'),
            'most_popular' => $this->model->orderBy('usage_count', 'desc')->first(),
            'categories' => $this->model->groupBy('category')->selectRaw('category, count(*) as count')->get(),
            'styles' => $this->model->groupBy('style')->selectRaw('style, count(*) as count')->get(),
        ];
    }

    public function duplicateTemplate(Template $template): Template
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->is_active = false;
        $newTemplate->usage_count = 0;
        $newTemplate->save();
        
        return $newTemplate;
    }

    public function toggleActive(Template $template): Template
    {
        $template->is_active = !$template->is_active;
        $template->save();
        return $template->fresh();
    }

    public function toggleRecommended(Template $template): Template
    {
        $template->is_recommended = !$template->is_recommended;
        $template->save();
        return $template->fresh();
    }

    public function updateTemplateSettings(Template $template, array $settings): Template
    {
        $currentSettings = $template->settings ?? [];
        $newSettings = array_merge($currentSettings, $settings);
        return $this->update($template, ['settings' => $newSettings]);
    }

    public function getTemplateDependencies(Template $template): array
    {
        return [
            'required_php_version' => $template->required_php_version ?? '7.4',
            'required_laravel_version' => $template->required_laravel_version ?? '8.0',
            'dependencies' => $template->dependencies ?? [],
            'composer_requirements' => $template->composer_requirements ?? [],
            'npm_requirements' => $template->npm_requirements ?? [],
        ];
    }
}