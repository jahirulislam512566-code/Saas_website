<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share theme data with all views
        View::composer('*', function ($view) {
            $theme = Config::get('themes.active', 'restaurant');
            $themeConfig = Config::get("themes.themes.{$theme}", []);
            
            $view->with([
                'activeTheme' => $theme,
                'themeConfig' => $themeConfig,
                'navigation' => $themeConfig['navigation'] ?? [],
                'footerLinks' => $themeConfig['footer'] ?? [
                    ['title' => 'Quick Links', 'links' => [
                        ['label' => 'Home', 'url' => '/'],
                        ['label' => 'About', 'url' => '/about'],
                        ['label' => 'Contact', 'url' => '/contact'],
                    ]]
                ]
            ]);
        });
    }
}