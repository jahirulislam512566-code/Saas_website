<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class BaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public $backoff = [60, 300, 600];

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public $maxExceptions = 3;

    /**
     * Log job processing.
     */
    protected function logStart(): void
    {
        Log::info('Job started', [
            'job' => class_basename($this),
            'data' => $this->getJobData(),
        ]);
    }

    /**
     * Log job completion.
     */
    protected function logComplete(): void
    {
        Log::info('Job completed', [
            'job' => class_basename($this),
            'data' => $this->getJobData(),
        ]);
    }

    /**
     * Log job failure.
     */
    protected function logError(\Throwable $e): void
    {
        Log::error('Job failed', [
            'job' => class_basename($this),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'data' => $this->getJobData(),
        ]);
    }

    /**
     * Get job data for logging.
     */
    protected function getJobData(): array
    {
        return [];
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->logError($exception);
        
        // Notify admin about job failure
        // Notification::send(Admin::all(), new JobFailedNotification($this, $exception));
    }
}