<?php

namespace App\Actions\Portfolio;

use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeaturePortfolioAction
{
    /**
     * Feature or unfeature a portfolio item
     *
     * @param Portfolio $portfolio
     * @param array $data
     * @return Portfolio
     */
    public function execute(Portfolio $portfolio, array $data): Portfolio
    {
        return DB::transaction(function () use ($portfolio, $data) {
            $oldStatus = $portfolio->is_featured;

            // If featuring, check limit
            if ($data['featured'] ?? true) {
                $featuredCount = Portfolio::where('is_featured', true)->count();
                $maxFeatured = $data['max_featured'] ?? 10;
                
                if ($featuredCount >= $maxFeatured) {
                    throw new \Exception('Maximum featured items limit reached.');
                }

                // Remove old featured if no longer needed
                if ($data['replace_old'] ?? true) {
                    Portfolio::where('is_featured', true)
                        ->orderBy('updated_at')
                        ->limit($featuredCount - $maxFeatured + 1)
                        ->update(['is_featured' => false]);
                }
            }

            $portfolio->update([
                'is_featured' => $data['featured'] ?? !$portfolio->is_featured,
                'featured_order' => $data['order'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            Log::info('Portfolio featured status updated', [
                'portfolio_id' => $portfolio->id,
                'title' => $portfolio->title,
                'old_status' => $oldStatus,
                'new_status' => $portfolio->is_featured,
                'updated_by' => auth()->id()
            ]);

            return $portfolio->fresh();
        });
    }
}