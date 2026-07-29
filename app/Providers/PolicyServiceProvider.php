<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class PolicyServiceProvider extends ServiceProvider
{
    /**
     * Register policy mappings.
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Customer::class => \App\Policies\CustomerPolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\Project::class => \App\Policies\ProjectPolicy::class,
        \App\Models\Invoice::class => \App\Policies\InvoicePolicy::class,
        \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
        \App\Models\Payment::class => \App\Policies\PaymentPolicy::class,
        \App\Models\BlogPost::class => \App\Policies\BlogPostPolicy::class,
        \App\Models\Portfolio::class => \App\Policies\PortfolioPolicy::class,
        \App\Models\Service::class => \App\Policies\ServicePolicy::class,
        \App\Models\Setting::class => \App\Policies\SettingPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define custom gates
        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        Gate::define('manage-projects', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('project_manager');
        });

        Gate::define('manage-invoices', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('accountant');
        });

        Gate::define('manage-orders', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('sales_manager');
        });

        Gate::define('view-reports', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('manager');
        });

        Gate::define('access-admin', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        Gate::define('access-customer', function ($user) {
            return $user->hasRole('customer') || $user->hasRole('admin');
        });

        // Define permission-based gates
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        Gate::after(function ($user, $ability, $result) {
            if ($user->hasRole('admin')) {
                return $result !== false;
            }
        });
    }

    /**
     * Register policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}