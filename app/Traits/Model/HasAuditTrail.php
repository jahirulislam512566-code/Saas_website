<?php

namespace App\Traits\Model;

use Illuminate\Support\Facades\Log;

trait HasAuditTrail
{
    /**
     * Boot the trait.
     */
    protected static function bootHasAuditTrail(): void
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updated(function ($model) {
            $model->logAudit('updated', $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });

        static::restored(function ($model) {
            $model->logAudit('restored');
        });
    }

    /**
     * Log audit trail.
     */
    public function logAudit(string $action, array $data = []): void
    {
        Log::channel('audit')->info('Model ' . $action, [
            'model' => get_class($this),
            'id' => $this->id,
            'action' => $action,
            'data' => $data,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get audit logs.
     */
    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'model');
    }

    /**
     * Get recent audit logs.
     */
    public function getRecentAuditLogs(int $limit = 10)
    {
        return $this->auditLogs()->latest()->limit($limit)->get();
    }
}