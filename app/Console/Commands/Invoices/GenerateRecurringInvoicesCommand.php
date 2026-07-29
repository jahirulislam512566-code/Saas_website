<?php

namespace App\Console\Commands\Invoices;

use App\Models\Invoice;
use App\Models\RecurringInvoice;
use App\Actions\Invoice\GenerateInvoiceAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateRecurringInvoicesCommand extends Command
{
    protected $signature = 'invoice:generate-recurring
                            {--dry-run : Show what would be generated without actually creating}';

    protected $description = 'Generate recurring invoices';

    public function handle(GenerateInvoiceAction $generateInvoiceAction)
    {
        $this->info('🔄 Generating recurring invoices...');

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No invoices will be created');
        }

        $recurringInvoices = RecurringInvoice::where('is_active', true)
            ->where('next_generation_date', '<=', now())
            ->with('customer')
            ->get();

        if ($recurringInvoices->isEmpty()) {
            $this->info('No recurring invoices to generate.');
            return Command::SUCCESS;
        }

        $this->info("Found {$recurringInvoices->count()} recurring invoices to generate.");

        $generated = 0;
        $failed = 0;

        foreach ($recurringInvoices as $recurring) {
            try {
                if (!$dryRun) {
                    $data = [
                        'customer_id' => $recurring->customer_id,
                        'items' => $recurring->items,
                        'tax_rate' => $recurring->tax_rate ?? 0,
                        'discount' => $recurring->discount ?? 0,
                        'currency' => $recurring->currency ?? 'USD',
                        'due_date' => now()->addDays($recurring->due_days ?? 30),
                    ];

                    $invoice = $generateInvoiceAction->execute($data);

                    // Update next generation date
                    $recurring->update([
                        'next_generation_date' => $this->calculateNextDate($recurring),
                        'last_generated_at' => now(),
                        'last_generated_invoice_id' => $invoice->id,
                    ]);

                    $generated++;
                    $this->line("  ✓ Generated invoice #{$invoice->invoice_number} for customer #{$recurring->customer_id}");
                } else {
                    $generated++;
                    $this->line("  ○ Would generate invoice for customer #{$recurring->customer_id}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed for customer #{$recurring->customer_id}: {$e->getMessage()}");
                Log::error('Failed to generate recurring invoice', [
                    'recurring_id' => $recurring->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();
        $this->info("✅ Generated: {$generated}, Failed: {$failed}");

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN completed. No actual invoices were created.');
        }

        Log::info('Recurring invoices generated', [
            'generated' => $generated,
            'failed' => $failed,
            'dry_run' => $dryRun,
        ]);

        return Command::SUCCESS;
    }

    private function calculateNextDate($recurring): \Carbon\Carbon
    {
        switch ($recurring->frequency) {
            case 'daily':
                return now()->addDay();
            case 'weekly':
                return now()->addWeek();
            case 'monthly':
                return now()->addMonth();
            case 'quarterly':
                return now()->addMonths(3);
            case 'yearly':
                return now()->addYear();
            default:
                return now()->addMonth();
        }
    }
}