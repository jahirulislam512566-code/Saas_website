<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup tasks
        $schedule->command('backup:daily --include-db --include-files --compress')
            ->dailyAt('02:00')
            ->description('Daily backup of application and database');

        $schedule->command('backup:cleanup --days=30')
            ->dailyAt('03:00')
            ->description('Clean up old backups');

        // Report tasks
        $schedule->command('report:weekly')
            ->weekly()
            ->mondays()
            ->at('08:00')
            ->description('Generate and send weekly report');

        $schedule->command('report:monthly')
            ->monthly()
            ->onFirstDayOfMonth()
            ->at('08:00')
            ->description('Generate and send monthly report');

        // Invoice tasks
        $schedule->command('invoice:monthly-reminder')
            ->dailyAt('09:00')
            ->description('Send monthly invoice reminders');

        $schedule->command('invoice:overdue-reminder')
            ->dailyAt('09:30')
            ->description('Send overdue invoice reminders');

        $schedule->command('invoice:generate-recurring')
            ->dailyAt('01:00')
            ->description('Generate recurring invoices');

        // Maintenance tasks
        $schedule->command('maintenance:clear-cache --all')
            ->dailyAt('04:00')
            ->description('Clear all caches');

        $schedule->command('maintenance:optimize-database --analyze')
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->description('Optimize database tables');

        $schedule->command('maintenance:cleanup-temp --days=7')
            ->dailyAt('05:00')
            ->description('Clean up temporary files');

        // User management tasks
        $schedule->command('user:deactivate-inactive --days=90')
            ->dailyAt('06:00')
            ->description('Deactivate inactive users');

        // Notification tasks
        $schedule->command('notifications:send-digest')
            ->weekly()
            ->fridays()
            ->at('10:00')
            ->description('Send weekly digest notifications');

        // Monitoring tasks
        $schedule->command('horizon:snapshot')
            ->everyFiveMinutes()
            ->description('Take Horizon snapshot');

        $schedule->command('telescope:prune --hours=48')
            ->daily()
            ->description('Prune Telescope entries');

        // Custom schedule
        $schedule->command('queue:work --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping()
            ->description('Process queue jobs');

        // If running in production environment
        if ($this->environment('production')) {
            $schedule->command('backup:daily --include-db --include-files --compress --destination=s3')
                ->dailyAt('02:00')
                ->description('Daily backup to S3');

            $schedule->command('horizon:snapshot')
                ->everyFiveMinutes()
                ->description('Take Horizon snapshot');
        }

        // Log schedule summary
        $schedule->command('schedule:list')
            ->dailyAt('00:00')
            ->description('Log scheduled tasks');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}