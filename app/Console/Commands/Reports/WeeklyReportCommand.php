<?php

namespace App\Console\Commands\Reports;

use App\Mail\WeeklyReportMail;
use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class WeeklyReportCommand extends Command
{
    protected $signature = 'report:weekly
                            {--email=* : Specific email addresses to send to}
                            {--format=html : Report format (html, pdf, json)}
                            {--save : Save report to storage}';

    protected $description = 'Generate and send weekly report';

    public function handle()
    {
        $this->info('📊 Generating weekly report...');
        
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        // Gather data
        $data = $this->gatherData($startDate, $endDate);

        // Generate report
        $report = $this->generateReport($data, $startDate, $endDate);

        // Save if requested
        if ($this->option('save')) {
            $this->saveReport($report);
        }

        // Send emails
        $this->sendReport($report);

        Log::info('Weekly report generated', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'format' => $this->option('format')
        ]);

        $this->info('✅ Weekly report generated and sent successfully!');

        return Command::SUCCESS;
    }

    private function gatherData($startDate, $endDate): array
    {
        $this->info('📊 Gathering data...');
        
        return [
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'orders' => [
                'total' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
                'total_amount' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
                'completed' => Order::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->count(),
                'cancelled' => Order::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'cancelled')
                    ->count(),
            ],
            'customers' => [
                'new' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
                'total' => Customer::count(),
            ],
            'payments' => [
                'total' => Payment::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->sum('amount'),
                'count' => Payment::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->count(),
            ],
            'users' => [
                'new' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
                'active' => User::where('is_active', true)->count(),
            ],
            'top_products' => $this->getTopProducts($startDate, $endDate),
            'top_customers' => $this->getTopCustomers($startDate, $endDate),
        ];
    }

    private function getTopProducts($startDate, $endDate): array
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', \DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getTopCustomers($startDate, $endDate): array
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('customers.name', \DB::raw('SUM(orders.total) as total_spent'))
            ->groupBy('customers.id', 'customers.name')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function generateReport(array $data, $startDate, $endDate): string
    {
        $format = $this->option('format');
        
        switch ($format) {
            case 'json':
                return json_encode($data, JSON_PRETTY_PRINT);
            case 'pdf':
                return $this->generatePdfReport($data);
            default:
                return $this->generateHtmlReport($data);
        }
    }

    private function generateHtmlReport(array $data): string
    {
        $html = view('reports.weekly', $data)->render();
        return $html;
    }

    private function generatePdfReport(array $data): string
    {
        // Use a PDF library like DomPDF or TCPDF
        // $pdf = \PDF::loadView('reports.weekly', $data);
        // return $pdf->output();
        return 'PDF content would be here';
    }

    private function saveReport(string $report): void
    {
        $filename = 'weekly_report_' . date('Y-m-d') . '.' . $this->option('format');
        $path = storage_path('reports/' . date('Y/m'));
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        File::put($path . '/' . $filename, $report);
        $this->info("📁 Report saved to: {$path}/{$filename}");
    }

    private function sendReport(string $report): void
    {
        $recipients = $this->option('email');
        
        if (empty($recipients)) {
            $recipients = config('mail.reports.weekly', []);
        }

        if (empty($recipients)) {
            $this->warn('No recipients specified. Report not sent.');
            return;
        }

        $this->info("📧 Sending report to " . count($recipients) . " recipients...");

        foreach ($recipients as $email) {
            Mail::to($email)->send(new WeeklyReportMail($report));
            $this->line("  ✓ Sent to {$email}");
        }
    }
}