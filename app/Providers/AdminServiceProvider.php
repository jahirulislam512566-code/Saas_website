// app/Providers/AdminServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Post;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Ticket;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share sidebar data with admin layout
        View::composer('admin.layouts.sidebar', function ($view) {
            $view->with([
                'totalUsers' => User::count(),
                'activeSubscriptions' => Subscription::where('status', 'active')->count(),
                'draftPosts' => Post::where('status', 'draft')->count(),
                'openTickets' => Ticket::where('status', 'open')->count(),
            ]);
        });
        
        // Share with main layout
        View::composer('admin.layouts.main', function ($view) {
            $view->with([
                'totalUsers' => User::count(),
                'activeSubscriptions' => Subscription::where('status', 'active')->count(),
                'draftPosts' => Post::where('status', 'draft')->count(),
                'openTickets' => Ticket::where('status', 'open')->count(),
            ]);
        });
    }
}