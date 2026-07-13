<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'metric_name',
        'value',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'value' => 'integer',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
    ];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Scopes
    public function scopeByMetric($query, $metric)
    {
        return $query->where('metric_name', $metric);
    }

    public function scopeCurrentPeriod($query)
    {
        return $query->where('period_start', '<=', now())
            ->where('period_end', '>=', now());
    }

    // Helper methods
    public function getFormattedValueAttribute()
    {
        $metrics = [
            'api_calls' => number_format($this->value),
            'storage_used' => $this->formatStorage($this->value),
            'users_count' => number_format($this->value),
        ];
        return $metrics[$this->metric_name] ?? $this->value;
    }

    protected function formatStorage($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}