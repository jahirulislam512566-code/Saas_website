<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Widget;

class WidgetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('admin.dashboard', function ($view) {
            $widgets = $this->getWidgets();
            $view->with('widgets', $widgets);
        });
    }

    protected function getWidgets()
    {
        return [
            'topWidgets' => $this->getTopWidgets(),
            'middleWidgets' => $this->getMiddleWidgets(),
            'bottomWidgets' => $this->getBottomWidgets(),
        ];
    }

    protected function getTopWidgets()
    {
        $widgets = [];

        // Example: Revenue Chart Widget (can be moved to separate file)
        $widgets[] = [
            'class' => 'lg:col-span-2',
            'content' => $this->renderRevenueWidget(),
        ];

        // Example: Recent Activity Widget
        $widgets[] = [
            'class' => '',
            'content' => $this->renderActivityWidget(),
        ];

        // You can add more widgets from different modules
        // $widgets = array_merge($widgets, $this->loadWidgetsFromModule('support'));

        return $widgets;
    }

    protected function getMiddleWidgets()
    {
        $widgets = [];

        // Example: User Statistics
        $widgets[] = [
            'class' => '',
            'content' => $this->renderUserStatsWidget(),
        ];

        // Example: Payment Statistics
        $widgets[] = [
            'class' => '',
            'content' => $this->renderPaymentStatsWidget(),
        ];

        return $widgets;
    }

    protected function getBottomWidgets()
    {
        $widgets = [];

        // Example: Recent Subscriptions
        $widgets[] = [
            'class' => '',
            'content' => $this->renderSubscriptionsWidget(),
        ];

        return $widgets;
    }

    protected function renderRevenueWidget()
    {
        // You can move this to a separate view
        return view('admin.widgets.revenue-chart', [
            'chartLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'chartData' => [1200, 1900, 1500, 2200, 2800, 3500],
        ])->render();
    }

    protected function renderActivityWidget()
    {
        $activities = \App\Models\ActivityLog::latest()->take(5)->get();
        return view('admin.widgets.recent-activity', ['activities' => $activities])->render();
    }

    protected function renderUserStatsWidget()
    {
        $totalUsers = \App\Models\User::count();
        $newUsers = \App\Models\User::whereMonth('created_at', now()->month)->count();
        return view('admin.widgets.user-stats', compact('totalUsers', 'newUsers'))->render();
    }

    protected function renderPaymentStatsWidget()
    {
        $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = \App\Models\Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        return view('admin.widgets.payment-stats', compact('totalRevenue', 'monthlyRevenue'))->render();
    }

    protected function renderSubscriptionsWidget()
    {
        $subscriptions = \App\Models\Subscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();
        return view('admin.widgets.recent-subscriptions', ['subscriptions' => $subscriptions])->render();
    }

    protected function loadWidgetsFromModule($module)
    {
        // Dynamically load widgets from module
        $widgetClass = "Modules\\{$module}\\Widgets\\DashboardWidgets";
        if (class_exists($widgetClass)) {
            return app($widgetClass)->getWidgets();
        }
        return [];
    }
}