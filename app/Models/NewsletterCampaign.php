<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'subject',
        'content',
        'status',
        'recipients',
        'scheduled_at',
        'sent_at',
        'sent_count',
        'open_count',
        'click_count',
        'bounce_count',
        'unsubscribe_count',
        'metadata',
    ];

    protected $casts = [
        'recipients' => 'array',
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'sent_count' => 'integer',
        'open_count' => 'integer',
        'click_count' => 'integer',
        'bounce_count' => 'integer',
        'unsubscribe_count' => 'integer',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(Newsletter::class, 'newsletter_subscriber_campaign')
            ->withPivot('opened', 'clicked', 'unsubscribed', 'opened_at', 'clicked_at', 'unsubscribed_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    // Helper methods
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function getOpenRateAttribute()
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->open_count / $this->sent_count) * 100, 2);
    }

    public function getClickRateAttribute()
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->click_count / $this->sent_count) * 100, 2);
    }

    public function getUnsubscribeRateAttribute()
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->unsubscribe_count / $this->sent_count) * 100, 2);
    }
}