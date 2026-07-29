<?php

namespace App\Providers;

use App\Services\Payment\PaymentService;
use App\Services\Email\EmailService;
use App\Services\Media\MediaService;
use App\Services\Seo\SeoService;
use App\Services\Search\SearchService;
use App\Services\Notification\NotificationService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Payment Service
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService(config('payment'));
        });

        // Email Service
        $this->app->singleton(EmailService::class, function ($app) {
            return new EmailService(config('mail'));
        });

        // Media Service
        $this->app->singleton(MediaService::class, function ($app) {
            return new MediaService(config('media'));
        });

        // SEO Service
        $this->app->singleton(SeoService::class, function ($app) {
            return new SeoService(
                $app->make(\App\Services\Seo\Analyzers\KeywordAnalyzer::class),
                $app->make(\App\Services\Seo\Analyzers\ContentAnalyzer::class),
                $app->make(\App\Services\Seo\SitemapGenerator::class)
            );
        });

        // Search Service
        $this->app->singleton(SearchService::class, function ($app) {
            return new SearchService(config('search'));
        });

        // Notification Service
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService(config('notification'));
        });

        // Register service facades
        $this->app->alias(PaymentService::class, 'payment');
        $this->app->alias(EmailService::class, 'email');
        $this->app->alias(MediaService::class, 'media');
        $this->app->alias(SeoService::class, 'seo');
        $this->app->alias(SearchService::class, 'search');
        $this->app->alias(NotificationService::class, 'notification');
    }

    public function boot(): void
    {
        //
    }
}