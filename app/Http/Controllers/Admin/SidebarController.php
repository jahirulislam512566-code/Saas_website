<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Ticket;
use Illuminate\Support\Facades\View;

class SidebarController extends Controller
{
    public function __construct()
    {
        // Share sidebar data with all admin views
        View::composer('admin.layouts.sidebar', function ($view) {
            $view->with([
                'totalUsers' => User::count(),
                'activeSubscriptions' => Subscription::where('status', 'active')->count(),
                'draftPosts' => Post::where('status', 'draft')->count(),
                'openTickets' => Ticket::where('status', 'open')->count(),
            ]);
        });
    }
}