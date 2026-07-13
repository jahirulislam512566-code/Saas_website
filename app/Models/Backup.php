<?php
// app/Models/Backup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'file_name',
        'file_path',
        'size',
        'status',
        'notes',
        'completed_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'size' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_size',
        'type_label',
        'status_label',
        'status_color',
        'icon',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        $bytes = $this->size ?? 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'database' => 'Database',
            'files' => 'Files',
            'full' => 'Full Backup',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'completed' => 'green',
            'processing' => 'yellow',
            'failed' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'database' => 'fa-database',
            'files' => 'fa-folder',
            'full' => 'fa-archive',
        ];
        return $icons[$this->type] ?? 'fa-archive';
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getFileSize(): string
    {
        return $this->formatted_size;
    }

    public function getDownloadUrl(): string
    {
        return route('admin.backups.download', $this);
    }

    public function deleteFile(): bool
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            return Storage::delete($this->file_path);
        }
        return true;
    }
}