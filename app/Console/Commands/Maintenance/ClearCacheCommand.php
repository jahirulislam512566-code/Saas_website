<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCacheCommand extends Command
{
    protected $signature = 'maintenance:clear-cache
                            {--all : Clear all caches}
                            {--views : Clear views cache}
                            {--routes : Clear routes cache}
                            {--config : Clear config cache}
                            {--events : Clear events cache}
                            {--app : Clear application cache}';

    protected $description = 'Clear various caches';

    public function handle()
    {
        $this->info('🧹 Clearing caches...');

        $cleared = [];

        // Clear views
        if ($this->option('all') || $this->option('views')) {
            Artisan::call('view:clear');
            $cleared['views'] = '✅';
            $this->info('  ✓ Views cache cleared');
        }

        // Clear routes
        if ($this->option('all') || $this->option('routes')) {
            Artisan::call('route:clear');
            $cleared['routes'] = '✅';
            $this->info('  ✓ Routes cache cleared');
        }

        // Clear config
        if ($this->option('all') || $this->option('config')) {
            Artisan::call('config:clear');
            $cleared['config'] = '✅';
            $this->info('  ✓ Config cache cleared');
        }

        // Clear events
        if ($this->option('all') || $this->option('events')) {
            Artisan::call('event:clear');
            $cleared['events'] = '✅';
            $this->info('  ✓ Events cache cleared');
        }

        // Clear application cache
        if ($this->option('all') || $this->option('app')) {
            Cache::flush();
            $cleared['app'] = '✅';
            $this->info('  ✓ Application cache cleared');
        }

        Log::info('Cache cleared', $cleared);

        $this->newLine();
        $this->info('✅ Cache clearing completed!');

        return Command::SUCCESS;
    }
}