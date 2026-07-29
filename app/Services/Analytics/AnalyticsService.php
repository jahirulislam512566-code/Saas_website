<?php

namespace App\Services\Analytics;

use App\Models\Analytics;
use App\Models\Website;
use Illuminate\Support\Facades\Http;

class AnalyticsService
{
    public function trackPageView(Website $website, array $data): void
    {
        Analytics::create([
            'website_id' => $website->id,
            'page_url' => $data['url'],
            'ip_address' => $data['ip'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'referrer' => $data['referrer'] ?? request()->header('referer'),
            'device_type' => $this->getDeviceType(request()->userAgent()),
            'browser' => $this->getBrowser(request()->userAgent()),
            'session_id' => $data['session_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'event_type' => 'page_view',
            'event_data' => $data,
            'timestamp' => now(),
        ]);
    }

    public function trackEvent(Website $website, string $eventType, array $data): void
    {
        Analytics::create([
            'website_id' => $website->id,
            'page_url' => $data['url'] ?? request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $this->getDeviceType(request()->userAgent()),
            'browser' => $this->getBrowser(request()->userAgent()),
            'event_type' => $eventType,
            'event_data' => $data,
            'timestamp' => now(),
        ]);
    }

    public function getWebsiteStats(Website $website, string $period = '7d'): array
    {
        $startDate = $this->getStartDate($period);
        $analytics = Analytics::where('website_id', $website->id)
            ->where('timestamp', '>=', $startDate)
            ->get();

        return [
            'total_visits' => $this->countUniqueVisits($analytics),
            'total_pageviews' => $analytics->count(),
            'bounce_rate' => $this->calculateBounceRate($analytics),
            'avg_time_on_page' => $this->calculateAvgTime($analytics),
            'devices' => $this->getDeviceStats($analytics),
            'browsers' => $this->getBrowserStats($analytics),
            'top_pages' => $this->getTopPages($analytics),
            'traffic_sources' => $this->getTrafficSources($analytics),
        ];
    }

    public function getRealTimeVisitors(Website $website): int
    {
        $last5Minutes = now()->subMinutes(5);
        
        return Analytics::where('website_id', $website->id)
            ->where('timestamp', '>=', $last5Minutes)
            ->distinct('session_id')
            ->count('session_id');
    }

    public function getVisitorInsights(Website $website, string $period = '30d'): array
    {
        $startDate = $this->getStartDate($period);
        
        return Analytics::where('website_id', $website->id)
            ->where('timestamp', '>=', $startDate)
            ->selectRaw('DATE(timestamp) as date, COUNT(DISTINCT session_id) as unique_visitors, COUNT(*) as page_views')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function getStartDate(string $period): \DateTime
    {
        return match($period) {
            '24h' => now()->subHours(24),
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(7),
        };
    }

    private function getDeviceType(string $userAgent): string
    {
        if (strpos($userAgent, 'Mobile') !== false) return 'mobile';
        if (strpos($userAgent, 'Tablet') !== false) return 'tablet';
        return 'desktop';
    }

    private function getBrowser(string $userAgent): string
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'Opera') !== false) return 'Opera';
        return 'Other';
    }

    private function countUniqueVisits($analytics): int
    {
        return $analytics->unique('session_id')->count();
    }

    private function calculateBounceRate($analytics): float
    {
        $sessions = $analytics->groupBy('session_id');
        $bounces = $sessions->filter(function($session) {
            return $session->count() === 1;
        });
        
        return $sessions->count() > 0 
            ? round(($bounces->count() / $sessions->count()) * 100, 2)
            : 0;
    }

    private function calculateAvgTime($analytics): float
    {
        // Implement average time calculation
        return 0;
    }

    private function getDeviceStats($analytics): array
    {
        return $analytics->groupBy('device_type')
            ->map(fn($group) => $group->count())
            ->toArray();
    }

    private function getBrowserStats($analytics): array
    {
        return $analytics->groupBy('browser')
            ->map(fn($group) => $group->count())
            ->toArray();
    }

    private function getTopPages($analytics): array
    {
        return $analytics->groupBy('page_url')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(10)
            ->toArray();
    }

    private function getTrafficSources($analytics): array
    {
        return $analytics->groupBy('referrer')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(5)
            ->toArray();
    }
}