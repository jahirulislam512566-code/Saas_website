<?php
// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'folder_id',
        'user_id',
        'model_type',
        'model_id',
        'uuid',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'path',
        'url',
        'thumbnail_path',
        'thumbnail_url',
        'size',
        'alt_text',
        'description',
        'visibility',
        'metadata',
    ];

    protected $casts = [
        'size' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_size',
        'is_image',
        'is_video',
        'is_audio',
        'extension',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'LIKE', 'image/%');
    }

    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'LIKE', 'video/%');
    }

    public function scopeAudios($query)
    {
        return $query->where('mime_type', 'LIKE', 'audio/%');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $bytes = $this->size;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsVideoAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    public function getIsAudioAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'audio/');
    }

    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    // ============================================
    // CUSTOM METHODS
    // ============================================

    public function getFullUrl(): string
    {
        return $this->url;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail_url ?? $this->url;
    }

    public function deleteFile(): bool
    {
        if (Storage::disk($this->disk)->exists($this->path)) {
            Storage::disk($this->disk)->delete($this->path);
        }
        
        if ($this->thumbnail_path && Storage::disk($this->disk)->exists($this->thumbnail_path)) {
            Storage::disk($this->disk)->delete($this->thumbnail_path);
        }
        
        return true;
    }
}