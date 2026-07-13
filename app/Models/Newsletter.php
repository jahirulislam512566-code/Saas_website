<?php

namespace App\Models;

use App\Models\NewsletterCampaign;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'email',
        'name',
        'preferences',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'ip_address',
    ];

    protected $casts = [
        'preferences' => 'array',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(NewsletterCampaign::class, 'newsletter_subscriber_campaign')
            ->withPivot('opened', 'clicked', 'unsubscribed', 'opened_at', 'clicked_at', 'unsubscribed_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isUnsubscribed()
    {
        return $this->status === 'unsubscribed';
    }

    public function unsubscribe()
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }

    public function subscribe()
    {
        $this->update([
            'status' => 'active',
            'unsubscribed_at' => null,
        ]);
    }
}