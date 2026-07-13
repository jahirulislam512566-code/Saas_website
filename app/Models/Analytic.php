<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'website_id',
        'page_url',
        'page_title',
        'referrer',
        'ip_address',
        'device_type',
        'browser',
        'os',
        'country_code',
        'city',
        'session_duration',
        'metadata',
        'visited_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'session_duration' => 'integer',
        'visited_at' => 'datetime',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year);
    }

    public function scopeByDevice($query, $device)
    {
        return $query->where('device_type', $device);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country_code', $country);
    }

    // Helper methods
    public function getDeviceIconAttribute()
    {
        $icons = [
            'desktop' => 'fa-desktop',
            'mobile' => 'fa-mobile-alt',
            'tablet' => 'fa-tablet-alt',
        ];
        return $icons[$this->device_type] ?? 'fa-device';
    }

    public function getBrowserIconAttribute()
    {
        $icons = [
            'Chrome' => 'fa-chrome',
            'Firefox' => 'fa-firefox',
            'Safari' => 'fa-safari',
            'Edge' => 'fa-edge',
            'Opera' => 'fa-opera',
        ];
        return $icons[$this->browser] ?? 'fa-browser';
    }
}