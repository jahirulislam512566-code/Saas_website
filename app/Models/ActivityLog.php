<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'subject_name',
        'description',
        'properties',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the activity log.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'System',
        ]);
    }

    /**
     * Get the subject of the activity (polymorphic).
     */
    public function subject()
    {
        if ($this->subject_type && $this->subject_id) {
            return $this->morphTo('subject', 'subject_type', 'subject_id');
        }
        return null;
    }

    /**
     * Get the action icon.
     */
    public function getIconAttribute(): string
    {
        return match ($this->action) {
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'payment' => 'fa-credit-card',
            'subscription' => 'fa-receipt',
            'user' => 'fa-user-plus',
            'ticket' => 'fa-ticket-alt',
            'status_changed' => 'fa-exchange-alt',
            'imported' => 'fa-file-import',
            'exported' => 'fa-file-export',
            'uploaded' => 'fa-upload',
            'downloaded' => 'fa-download',
            'viewed' => 'fa-eye',
            'commented' => 'fa-comment',
            'assigned' => 'fa-user-check',
            'resolved' => 'fa-check-circle',
            'closed' => 'fa-times-circle',
            'reopened' => 'fa-undo',
            'approved' => 'fa-check-double',
            'rejected' => 'fa-times',
            'published' => 'fa-globe',
            'unpublished' => 'fa-eye-slash',
            'archived' => 'fa-archive',
            'restored' => 'fa-undo-alt',
            'permission_changed' => 'fa-lock',
            'role_changed' => 'fa-user-tag',
            'email_sent' => 'fa-envelope',
            'notification_sent' => 'fa-bell',
            'backup_created' => 'fa-database',
            'backup_restored' => 'fa-database',
            'cache_cleared' => 'fa-trash-alt',
            'optimized' => 'fa-rocket',
            'maintenance_toggled' => 'fa-tools',
            default => 'fa-clock',
        };
    }

    /**
     * Get the action color.
     */
    public function getColorAttribute(): string
    {
        return match ($this->action) {
            'created', 'login', 'payment', 'imported', 'uploaded', 'approved', 'published', 'restored', 'backup_created' => 'success',
            'updated', 'status_changed', 'exported', 'viewed', 'assigned', 'role_changed', 'email_sent', 'notification_sent' => 'info',
            'deleted', 'logout', 'rejected', 'unpublished', 'archived' => 'danger',
            'closed', 'permission_changed', 'cache_cleared', 'maintenance_toggled' => 'warning',
            'resolved', 'reopened', 'commented', 'optimized' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get the activity status.
     */
    public function getStatusAttribute(): string
    {
        return match ($this->action) {
            'created', 'login', 'payment', 'imported', 'uploaded', 'approved', 'published', 'restored' => 'success',
            'deleted', 'logout', 'rejected', 'unpublished', 'archived' => 'danger',
            'updated', 'status_changed' => 'warning',
            'resolved', 'reopened' => 'completed',
            default => 'info',
        };
    }

    /**
     * Get formatted action name.
     */
    public function getFormattedActionAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->action));
    }

    /**
     * Get the user's name.
     */
    public function getUserNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'System';
    }

    /**
     * Get the user's email.
     */
    public function getUserEmailAttribute(): string
    {
        return $this->user ? $this->user->email : 'system@example.com';
    }

    /**
     * Get the subject name.
     */
    public function getSubjectNameAttribute(): string
    {
        if ($this->subject_name) {
            return $this->subject_name;
        }

        if ($this->subject_type && $this->subject_id) {
            $subject = $this->subject;
            if ($subject && method_exists($subject, 'getNameAttribute')) {
                return $subject->name;
            }
        }

        return 'Unknown';
    }

    /**
     * Check if activity is related to a specific subject.
     */
    public function isSubject($type, $id = null): bool
    {
        if ($this->subject_type !== $type) {
            return false;
        }

        if ($id !== null && $this->subject_id !== $id) {
            return false;
        }

        return true;
    }

    /**
     * Scope a query to only include activities for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include activities for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include activities of a specific action.
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include activities for a specific subject.
     */
    public function scopeForSubject($query, $type, $id = null)
    {
        $query->where('subject_type', $type);
        
        if ($id !== null) {
            $query->where('subject_id', $id);
        }
        
        return $query;
    }

    /**
     * Scope a query to only include activities within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include recent activities.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Log an activity.
     */
    public static function log(array $data)
    {
        $defaults = [
            'tenant_id' => auth()->check() ? auth()->user()->tenant_id : null,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        $data = array_merge($defaults, $data);

        return self::create($data);
    }

    /**
     * Log a created activity.
     */
    public static function logCreated($subject, $description = null)
    {
        return self::log([
            'action' => 'created',
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'subject_name' => method_exists($subject, 'getNameAttribute') ? $subject->name : null,
            'description' => $description ?? 'Created ' . class_basename($subject),
            'new_values' => $subject->toArray(),
        ]);
    }

    /**
     * Log an updated activity.
     */
    public static function logUpdated($subject, $oldValues = null, $description = null)
    {
        return self::log([
            'action' => 'updated',
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'subject_name' => method_exists($subject, 'getNameAttribute') ? $subject->name : null,
            'description' => $description ?? 'Updated ' . class_basename($subject),
            'old_values' => $oldValues ?? $subject->getOriginal(),
            'new_values' => $subject->toArray(),
        ]);
    }

    /**
     * Log a deleted activity.
     */
    public static function logDeleted($subject, $description = null)
    {
        return self::log([
            'action' => 'deleted',
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'subject_name' => method_exists($subject, 'getNameAttribute') ? $subject->name : null,
            'description' => $description ?? 'Deleted ' . class_basename($subject),
            'old_values' => $subject->toArray(),
        ]);
    }

    /**
     * Log a custom activity.
     */
    public static function logCustom($action, $description, $subject = null, $properties = null, $oldValues = null, $newValues = null)
    {
        $data = [
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ];

        if ($subject) {
            $data['subject_type'] = get_class($subject);
            $data['subject_id'] = $subject->id;
            $data['subject_name'] = method_exists($subject, 'getNameAttribute') ? $subject->name : null;
        }

        return self::log($data);
    }
}