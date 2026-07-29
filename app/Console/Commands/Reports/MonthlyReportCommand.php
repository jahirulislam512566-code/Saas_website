<?php

namespace App\Console\Commands\Reports;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MonthlyReportCommand extends Command
{
    protected $signature = 'report:monthly
                            {--year= : Year for the report}
                            {--month= : Month for the report}
                            {--email=* : Recipient emails}
                            {--include-charts : Include charts in report}';

    protected $description = 'Generate and send monthly report';

    public function handle()
    {
        $this->info('📊 Generating monthly report...');

        $year = $this->option('year') ?? now()->year;
        $month = $this->option('month') ?? now()->month;

        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        // Gather comprehensive data
        $data = $this->gatherData($startDate, $endDate);

        // Generate report
        $report = $this->generateReport($data, $startDate, $endDate);

        // Send to recipients
        $this->sendReport($report, $startDate);

        Log::info('Monthly report generated', [
            'year' => $year,
            'month' => $month,
            'data' => $data['summary']
        ]);

        $this->info('✅ Monthly report generated successfully!');

        return Command::SUCCESS;
    }

    private function gatherData($startDate, $endDate): array
    {
        $this->info('📊 Gathering monthly data...');

        $previousMonthStart = (clone $startDate)->subMonth();
        $previousMonthEnd = (clone $startDate)->subDay();

        return [
            'period' => [
                'month' => $startDate->format('F Y'),
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'summary' => [
                'total_revenue' => DB::table('orders')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->sum('total'),
                'total_orders' => DB::table('orders')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'average_order_value' => DB::table('orders')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->avg('total'),
                'new_customers' => DB::table('customers')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'total_customers' => DB::table('customers')->count(),
            ],
            'comparison' => [
                'previous_month_revenue' => DB::table('orders')
                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                    ->where('status', 'completed')
                    ->sum('total'),
                'previous_month_orders' => DB::table('orders')
                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                    ->count(),
            ],
            'charts' => $this->generateChartData($startDate, $endDate),
            'top_products' => $this->getTopProducts($startDate, $endDate),
            'top_customers' => $this->getTopCustomers($startDate, $endDate),
            'revenue_by_category' => $this->getRevenueByCategory($startDate, $endDate),
            'daily_orders' => $this->getDailyOrders($startDate, $endDate),
        ];
    }

    private function generateChartData($startDate, $endDate): array
    {
        $daysInMonth = $startDate->daysInMonth;
        $dailyRevenue = [];
        $dailyOrders = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = \Carbon\Carbon::create($startDate->year, $startDate->month, $i);
            
            $dailyRevenue[] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => DB::table('orders')
                    ->whereDate('created_at', $date)
                    ->where('status', 'completed')
                    ->sum('total'),
            ];

            $dailyOrders[] = [
                'date' => $date->format('Y-m-d'),
                'count' => DB::table('orders')
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        return [
            'daily_revenue' => $dailyRevenue,
            'daily_orders' => $dailyOrders,
        ];
    }

    private function getTopProducts($startDate, $endDate): array
    {
        return DB::table('orders')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getTopCustomers($startDate, $endDate): array
    {
        return DB::table('orders')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'customers.name',
                'customers.email',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getRevenueByCategory($startDate, $endDate): array
    {
        return DB::table('orders')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.total) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get()
            ->toArray();
    }

    private function getDailyOrders($startDate, $endDate): array
    {
        $days = [];
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $days[] = [
                'date' => $currentDate->format('Y-m-d'),
                'orders' => DB::table('orders')
                    ->whereDate('created_at', $currentDate)
                    ->count(),
            ];
            $currentDate->addDay();
        }

        return $days;
    }

    private function generateReport(array $data, $startDate, $endDate): string
    {
        return view('reports.monthly', [
            'data' => $data,
            'month' => $startDate->format('F Y'),
        ])->render();
    }

    private function sendReport(string $report, $startDate): void
    {
        $recipients = $this->option('email');
        
        if (empty($recipients)) {
            $recipients = config('mail.reports.monthly', []);
        }

        foreach ($recipients as $email) {
            Mail::send([], [], function ($message) use ($email, $report, $startDate) {
                $message->to($email)
                        ->subject("Monthly Report - {$startDate->format('F Y')}")
                        ->html($report);
            });
        }
    }
}