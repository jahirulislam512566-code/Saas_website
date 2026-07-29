<?php

namespace App\Console\Commands\Notifications;

use App\Models\User;
use App\Notifications\WeeklyDigestNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendWeeklyDigestCommand extends Command
{
    protected $signature = 'notifications:send-digest
                            {--dry-run : Show what would be sent without actually sending}';

    protected $description = 'Send weekly digest notifications to users';

    public function handle()
    {
        $this->info('📧 Sending weekly digest...');

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No notifications will be sent');
        }

        $users = User::where('is_active', true)
            ->where('receive_digest', true)
            ->get();

        if ($users->isEmpty()) {
            $this->info('No users to send digest to.');
            return Command::SUCCESS;
        }

        $this->info("Sending digest to {$users->count()} users.");

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                if (!$dryRun) {
                    Notification::send($user, new WeeklyDigestNotification($user));
                }
                $sent++;
                $this->line("  ✓ Sent to: {$user->email}");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed: {$user->email} - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("✅ Sent: {$sent}, Failed: {$failed}");

        Log::info('Weekly digest sent', [
            'sent' => $sent,
            'failed' => $failed,
            'dry_run' => $dryRun,
        ]);

        return Command::SUCCESS;
    }
}