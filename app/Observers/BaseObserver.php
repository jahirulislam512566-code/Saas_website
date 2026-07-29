<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;

abstract class BaseObserver
{
    /**
     * Log observer activity.
     */
    protected function log(string $action, $model, array $data = []): void
    {
        Log::info('Observer triggered', [
            'observer' => class_basename($this),
            'action' => $action,
            'model' => get_class($model),
            'model_id' => $model->id ?? null,
            'data' => $data,
        ]);
    }

    /**
     * Get user who performed the action.
     */
    protected function getUserId(): ?int
    {
        return auth()->id();
    }

    /**
     * Get IP address.
     */
    protected function getIpAddress(): ?string
    {
        return request()->ip();
    }
}