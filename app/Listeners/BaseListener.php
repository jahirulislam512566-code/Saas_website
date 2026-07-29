<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

abstract class BaseListener
{
    /**
     * Log listener processing.
     */
    protected function logStart(string $event, array $data = []): void
    {
        Log::info('Listener started', [
            'listener' => class_basename($this),
            'event' => $event,
            'data' => $data,
        ]);
    }

    /**
     * Log listener completion.
     */
    protected function logComplete(string $event, array $data = []): void
    {
        Log::info('Listener completed', [
            'listener' => class_basename($this),
            'event' => $event,
            'data' => $data,
        ]);
    }

    /**
     * Log listener error.
     */
    protected function logError(string $event, \Throwable $e, array $data = []): void
    {
        Log::error('Listener failed', [
            'listener' => class_basename($this),
            'event' => $event,
            'error' => $e->getMessage(),
            'data' => $data,
        ]);
    }
}