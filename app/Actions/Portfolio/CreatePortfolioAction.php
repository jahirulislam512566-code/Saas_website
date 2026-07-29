<?php

namespace App\Actions\Portfolio;

use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreatePortfolioAction
{
    /**
     * Create a new portfolio item
     *
     * @param array $data
     * @return Portfolio
     */
    public function execute(array $data): Portfolio
    {
        return DB::transaction(function () use ($data) {
            $slug = Str::slug($data['title']);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Portfolio::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $portfolio = Portfolio::create([
                'title' => $data['title'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'category' => $data['category'],
                'client' => $data['client'] ?? null,
                'project_type' => $data['project_type'] ?? null,
                'technologies' => $data['technologies'] ?? [],
                'featured_image' => $data['featured_image'] ?? null,
                'gallery' => $data['gallery'] ?? [],
                'project_url' => $data['project_url'] ?? null,
                'completion_date' => $data['completion_date'] ?? now(),
                'is_featured' => $data['is_featured'] ?? false,
                'is_active' => $data['is_active'] ?? true,
                'created_by' => auth()->id(),
            ]);

            Log::info('Portfolio item created', [
                'portfolio_id' => $portfolio->id,
                'title' => $portfolio->title,
                'category' => $portfolio->category,
                'created_by' => auth()->id()
            ]);

            return $portfolio;
        });
    }
}