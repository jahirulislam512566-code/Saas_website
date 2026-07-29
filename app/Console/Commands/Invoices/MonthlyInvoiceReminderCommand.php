<?php

namespace App\Console\Commands\Invoices;

use App\Models\Invoice;
use App\Notifications\InvoiceReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MonthlyInvoiceReminderCommand extends Command
{
    protected $signature = 'invoice:monthly-reminder
                            {--days=7 : Days before due date to send reminder}
                            {--dry-run : Show what would be sent without actually sending}';

    protected $description = 'Send monthly invoice reminders to customers';

    public function handle()
    {
        $this->info('📨 Sending monthly invoice reminders...');
        
        $daysBeforeDue = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No notifications will be sent');
        }

        $dueDate = now()->addDays($daysBeforeDue);
        
        $invoices = Invoice::where('status', 'pending')
            ->whereDate('due_date', '<=', $dueDate)
            ->where('reminder_sent', false)
            ->with('customer')
            ->get();

        if ($invoices->isEmpty()) {
            $this->info('No invoices need reminders.');
            return Command::SUCCESS;
        }

        $this->info("Found {$invoices->count()} invoices needing reminders.");

        $sent = 0;
        $failed = 0;

        foreach ($invoices as $invoice) {
            try {
                if (!$dryRun) {
                    Notification::send($invoice->customer, new InvoiceReminderNotification($invoice));
                    
                    $invoice->update([
                        'reminder_sent' => true,
                        'reminder_sent_at' => now(),
                    ]);
                }
                
                $sent++;
                $this->line("  ✓ Reminder sent for invoice #{$invoice->invoice_number}");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed for invoice #{$invoice->invoice_number}: {$e->getMessage()}");
                Log::error('Failed to send invoice reminder', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();
        $this->info("✅ Reminders processed: {$sent} sent, {$failed} failed");

        Log::info('Monthly invoice reminders sent', [
            'sent' => $sent,
            'failed' => $failed,
            'dry_run' => $dryRun,
        ]);

        return Command::SUCCESS;
    }
}