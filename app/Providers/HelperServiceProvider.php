<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Load all helpers
        foreach (glob(app_path('Helpers/*.php')) as $file) {
            if (basename($file) !== 'helpers.php') {
                require_once $file;
            }
        }

        require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}