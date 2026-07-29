<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register DTOs
        $this->app->bind(
            \App\Data\DTOs\CustomerDTO::class,
            \App\Data\DTOs\CustomerDTO::class
        );

        // Register repositories
        $this->app->bind(
            \App\Repositories\Contracts\CustomerRepositoryInterface::class,
            \App\Repositories\Eloquent\CustomerRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\OrderRepositoryInterface::class,
            \App\Repositories\Eloquent\OrderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ProjectRepositoryInterface::class,
            \App\Repositories\Eloquent\ProjectRepository::class
        );

        // Register services
        $this->app->singleton(
            \App\Services\PaymentGatewayService::class,
            function ($app) {
                return new \App\Services\PaymentGatewayService(
                    config('services.stripe.secret'),
                    config('services.stripe.webhook_secret')
                );
            }
        );

        $this->app->singleton(
            \App\Services\ReportGeneratorService::class,
            function ($app) {
                return new \App\Services\ReportGeneratorService(
                    $app->make(\App\Repositories\Contracts\OrderRepositoryInterface::class),
                    $app->make(\App\Repositories\Contracts\CustomerRepositoryInterface::class)
                );
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Set default string length for migrations
        Schema::defaultStringLength(191);

        // Prevent lazy loading in production
        if ($this->app->environment('production')) {
            Model::preventLazyLoading();
            Model::preventSilentlyDiscardingAttributes();
        }

        // Use Bootstrap 5 pagination
        Paginator::useBootstrapFive();

        // Custom collections
        $this->registerCollectionMacros();

        // Custom query builders
        $this->registerQueryMacros();

        // Register model observers
        $this->registerObservers();

        // Register policies
        $this->registerPolicies();

        // Register view composers
        $this->registerViewComposers();

        // Register validation rules
        $this->registerValidationRules();

        // Register scheduled tasks
        $this->registerScheduledTasks();
    }

    /**
     * Register collection macros.
     */
    protected function registerCollectionMacros(): void
    {
        \Illuminate\Support\Collection::macro('toMoney', function ($key) {
            return $this->map(function ($item) use ($key) {
                $item->{$key . '_formatted'} = money($item->{$key});
                return $item;
            });
        });

        \Illuminate\Support\Collection::macro('formatDates', function ($keys) {
            return $this->map(function ($item) use ($keys) {
                foreach ($keys as $key) {
                    if (isset($item->{$key})) {
                        $item->{$key . '_formatted'} = $item->{$key}->format('M d, Y');
                    }
                }
                return $item;
            });
        });
    }

    /**
     * Register query macros.
     */
    protected function registerQueryMacros(): void
    {
        \Illuminate\Database\Eloquent\Builder::macro('whereLike', function ($column, $search) {
            return $this->where($column, 'LIKE', "%{$search}%");
        });

        \Illuminate\Database\Eloquent\Builder::macro('whereInSearch', function ($columns, $search) {
            return $this->where(function ($query) use ($columns, $search) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        });
    }

    /**
     * Register model observers.
     */
    protected function registerObservers(): void
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Customer::observe(\App\Observers\CustomerObserver::class);
         \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Ticket::observe(\App\Observers\TicketObserver::class);
        \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
        \App\Models\BlogPost::observe(\App\Observers\BlogPostObserver::class);
        \App\Models\Portfolio::observe(\App\Observers\PortfolioObserver::class);
        \App\Models\Media::observe(\App\Observers\MediaObserver::class);
    }

    /**
     * Register policies.
     */
    protected function registerPolicies(): void
    {
        $this->app->make(\Illuminate\Contracts\Auth\Access\Gate::class)->before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers(): void
    {
        // Share company data with all views
        view()->composer('*', function ($view) {
            $view->with('company', company());
        });

        // Share notifications with authenticated users
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('notifications', auth()->user()->unreadNotifications->take(10));
                $view->with('notification_count', auth()->user()->unreadNotifications->count());
            }
        });

        // Share cart data with frontend views
        view()->composer('frontend.*', function ($view) {
            $view->with('cart_count', session()->get('cart_count', 0));
        });
    }

    /**
     * Register validation rules.
     */
    protected function registerValidationRules(): void
    {
        \Illuminate\Support\Facades\Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        });

        \Illuminate\Support\Facades\Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\+?[0-9\-\(\)\s]{10,20}$/', $value);
        });

        \Illuminate\Support\Facades\Validator::extend('valid_domain', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $value);
        });
    }

    /**
     * Register scheduled tasks.
     */
     protected function registerScheduledTasks(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(\Illuminate\Console\Scheduling\Schedule::class);
            
            // Daily backup - runs every day at 2:00 AM
            $schedule->command('backup:daily')->dailyAt('02:00');
            
            // Weekly reports - runs every Monday at 8:00 AM
            $schedule->command('report:weekly')->weekly()->mondays()->at('08:00');
            
            // Monthly invoices - runs on the 1st of every month at 9:00 AM
            $schedule->command('invoice:monthly-reminder')->monthlyOn(1, '09:00');
            
            // Cleanup tasks - runs daily at 5:00 AM
            $schedule->command('maintenance:cleanup-temp')->dailyAt('05:00');
            
            // Optimize database - runs every Sunday at 3:00 AM
            $schedule->command('maintenance:optimize-database')->weekly()->sundays()->at('03:00');
            
            // Generate recurring invoices - runs daily at 1:00 AM
            $schedule->command('invoice:generate-recurring')->dailyAt('01:00');
            
            // Send overdue reminders - runs daily at 9:30 AM
            $schedule->command('invoice:overdue-reminder')->dailyAt('09:30');
            
            // Deactivate inactive users - runs daily at 6:00 AM
            $schedule->command('user:deactivate-inactive')->dailyAt('06:00');
            
            // Clear cache - runs daily at 4:00 AM
            $schedule->command('maintenance:clear-cache --all')->dailyAt('04:00');
        });
    }
}