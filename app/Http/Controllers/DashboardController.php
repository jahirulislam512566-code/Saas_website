<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'total_projects' => Project::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        // Get latest payments
        $latestPayments = Payment::with('order.customer')
            ->latest()
            ->limit(10)
            ->get();

        // Get revenue data for charts
        $revenueData = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->selectRaw('DAY(created_at) as day, SUM(total) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $revenueLabels = $revenueData->pluck('day')->map(function($day) {
            return date('M d', strtotime(now()->format('Y-m-') . $day));
        })->toArray();

        $revenueValues = $revenueData->pluck('total')->toArray();

        // Get pie chart data
        $pieData = [
            'services' => Order::where('status', 'completed')->where('type', 'service')->sum('total'),
            'products' => Order::where('status', 'completed')->where('type', 'product')->sum('total'),
            'subscriptions' => Order::where('status', 'completed')->where('type', 'subscription')->sum('total'),
        ];

        return view('admin.dashboard.index', compact(
            'stats',
            'latestPayments',
            'revenueData',
            'revenueLabels',
            'revenueValues',
            'pieData'
        ));
    }
}