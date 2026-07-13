<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_submissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'data',
        'ip_address',
        'user_agent',
        'status',
        'read_at',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the form that the submission belongs to.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the user who submitted the form (if authenticated).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread submissions.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read submissions.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include spam submissions.
     */
    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    /**
     * Scope a query to only include new submissions.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope a query to only include replied submissions.
     */
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    /**
     * Mark the submission as read.
     */
    public function markAsRead()
    {
        $this->update([
            'read_at' => now(),
            'status' => 'read',
        ]);
    }

    /**
     * Mark the submission as spam.
     */
    public function markAsSpam()
    {
        $this->update(['status' => 'spam']);
    }

    /**
     * Mark the submission as replied.
     */
    public function markAsReplied()
    {
        $this->update(['status' => 'replied']);
    }

    /**
     * Check if the submission has been read.
     */
    public function isRead()
    {
        return $this->read_at !== null || $this->status === 'read';
    }

    /**
     * Check if the submission is new.
     */
    public function isNew()
    {
        return $this->status === 'new';
    }

    /**
     * Check if the submission is spam.
     */
    public function isSpam()
    {
        return $this->status === 'spam';
    }

    /**
     * Get the submission data as a formatted array.
     */
    public function getFormattedDataAttribute()
    {
        if (!$this->data) {
            return [];
        }

        $formatted = [];
        foreach ($this->data as $key => $value) {
            // Try to get field label from form definition
            $label = $key;
            if ($this->form && $this->form->fields) {
                foreach ($this->form->fields as $field) {
                    if ($field['name'] === $key) {
                        $label = $field['label'] ?? $key;
                        break;
                    }
                }
            }
            $formatted[] = [
                'label' => $label,
                'value' => is_array($value) ? implode(', ', $value) : $value,
                'key' => $key,
            ];
        }

        return $formatted;
    }

    /**
     * Get the submission summary (first few fields).
     */
    public function getSummaryAttribute()
    {
        if (!$this->data) {
            return 'No data';
        }

        $summary = [];
        $fields = array_slice($this->data, 0, 3, true);
        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                $summary[] = is_array($value) ? implode(', ', $value) : $value;
            }
        }

        return implode(' | ', $summary);
    }

    /**
     * Get the submission age in human readable format.
     */
    public function getAgeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'new' => 'badge bg-primary',
            'read' => 'badge bg-info',
            'replied' => 'badge bg-success',
            'spam' => 'badge bg-danger',
            default => 'badge bg-secondary',
        };
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get the user agent info (browser, OS, device).
     */
    public function getUserAgentInfoAttribute()
    {
        if (!$this->user_agent) {
            return null;
        }

        // You can use a package like "jenssegers/agent" for better parsing
        // For now, return the raw user agent
        return $this->user_agent;
    }

    /**
     * Get the IP location info.
     */
    public function getIpLocationAttribute()
    {
        if (!$this->ip_address) {
            return null;
        }

        // You can use a geo-ip service here
        return $this->ip_address;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($submission) {
            // Auto-set the status to 'new' if not set
            if (!$submission->status) {
                $submission->status = 'new';
            }
        });
    }
}