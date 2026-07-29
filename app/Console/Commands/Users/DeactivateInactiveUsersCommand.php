<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateInactiveUsersCommand extends Command
{
    protected $signature = 'user:deactivate-inactive
                            {--days=90 : Days of inactivity before deactivation}
                            {--dry-run : Show what would be done without actually doing}';

    protected $description = 'Deactivate inactive users';

    public function handle()
    {
        $this->info('👤 Deactivating inactive users...');

        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No users will be deactivated');
        }

        $cutoffDate = now()->subDays($days);
        
        $users = User::where('is_active', true)
            ->where('last_login_at', '<=', $cutoffDate)
            ->orWhereNull('last_login_at')
            ->get();

        if ($users->isEmpty()) {
            $this->info('No inactive users found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$users->count()} inactive users.");

        $deactivated = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                if (!$dryRun) {
                    $user->update([
                        'is_active' => false,
                        'deactivated_at' => now(),
                        'deactivation_reason' => 'Inactive for ' . $days . ' days',
                    ]);
                }
                
                $deactivated++;
                $this->line("  ✓ Deactivated: {$user->email}");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed: {$user->email} - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("✅ Deactivated: {$deactivated}, Failed: {$failed}");

        Log::info('Inactive users deactivated', [
            'deactivated' => $deactivated,
            'failed' => $failed,
            'days' => $days,
            'dry_run' => $dryRun,
        ]);

        return Command::SUCCESS;
    }
}