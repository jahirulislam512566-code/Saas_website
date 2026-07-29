<?php

namespace App\Console\Commands\Invoices;

use App\Models\Invoice;
use App\Notifications\OverdueInvoiceNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class OverdueInvoiceReminderCommand extends Command
{
    protected $signature = 'invoice:overdue-reminder
                            {--days=1 : Days overdue to start sending reminders}
                            {--dry-run : Show what would be sent without actually sending}';

    protected $description = 'Send reminders for overdue invoices';

    public function handle()
    {
        $this->info('📨 Sending overdue invoice reminders...');

        $daysOverdue = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No notifications will be sent');
        }

        $cutoffDate = now()->subDays($daysOverdue);
        
        $invoices = Invoice::where('status', 'pending')
            ->whereDate('due_date', '<', $cutoffDate)
            ->where('overdue_reminder_sent', false)
            ->with('customer')
            ->get();

        if ($invoices->isEmpty()) {
            $this->info('No overdue invoices found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$invoices->count()} overdue invoices.");

        foreach ($invoices as $invoice) {
            $daysLate = now()->diffInDays($invoice->due_date);
            
            if (!$dryRun) {
                Notification::send($invoice->customer, new OverdueInvoiceNotification($invoice, $daysLate));
                
                $invoice->update([
                    'overdue_reminder_sent' => true,
                    'overdue_reminder_sent_at' => now(),
                ]);
            }

            $this->line("  ✓ Reminder sent for invoice #{$invoice->invoice_number} ({$daysLate} days overdue)");
        }

        Log::info('Overdue invoice reminders sent', [
            'count' => $invoices->count(),
            'dry_run' => $dryRun,
        ]);

        $this->newLine();
        $this->info('✅ Overdue reminders sent successfully!');

        return Command::SUCCESS;
    }
}