<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Website;
use App\Models\Page;
use App\Models\Post;
use App\Models\Template;
use App\Models\Media;
use App\Policies\WebsitePolicy;
use App\Policies\PagePolicy;
use App\Policies\PostPolicy;
use App\Policies\TemplatePolicy;
use App\Policies\MediaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Website::class => WebsitePolicy::class,
        Page::class => PagePolicy::class,
        Post::class => PostPolicy::class,
        Template::class => TemplatePolicy::class,
        Media::class => MediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Super admin bypass all policies
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
        });

        // Define custom gates
        Gate::define('access-admin', function ($user) {
            return $user->hasRole('admin') || 
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-subscriptions', function ($user) {
            return $user->hasPermissionTo('manage subscriptions') || 
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-invoices', function ($user) {
            return $user->hasPermissionTo('manage invoices') || 
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-websites', function ($user) {
            return $user->hasPermissionTo('manage websites') || 
                   $user->hasRole('admin') ||
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-pages', function ($user) {
            return $user->hasPermissionTo('manage pages') || 
                   $user->hasRole('admin') ||
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-posts', function ($user) {
            return $user->hasPermissionTo('manage posts') || 
                   $user->hasRole('admin') ||
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-templates', function ($user) {
            return $user->hasPermissionTo('manage templates') || 
                   $user->hasRole('admin') ||
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });

        Gate::define('manage-media', function ($user) {
            return $user->hasPermissionTo('manage media') || 
                   $user->hasRole('admin') ||
                   (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin());
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}