<?php

namespace App\Models;

use App\Models\UsageMetric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Stripe\Invoice;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'canceled_at',
        'ends_at',
        'amount',
        'billing_cycle',
        'currency',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array<string>
     */
    protected $appends = [
        'status_label',
        'status_color',
        'formatted_amount',
        'days_left',
        'is_expiring_soon',
        'billing_cycle_label',
    ];

    /**
     * The status colors for badges.
     *
     * @var array<string, string>
     */
    protected static $statusColors = [
        'active' => 'green',
        'trialing' => 'blue',
        'past_due' => 'orange',
        'canceled' => 'red',
        'unpaid' => 'red',
        'incomplete' => 'gray',
        'paused' => 'yellow',
    ];

    /**
     * The status labels.
     *
     * @var array<string, string>
     */
    protected static $statusLabels = [
        'active' => 'Active',
        'trialing' => 'Trial',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
        'unpaid' => 'Unpaid',
        'incomplete' => 'Incomplete',
        'paused' => 'Paused',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that the subscription belongs to.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the payments for the subscription.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the usage metrics for the subscription.
     */
    public function usageMetrics()
    {
        return $this->hasMany(UsageMetric::class);
    }

    /**
     * Get the activities for the subscription.
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Get the invoices for the subscription.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'trialing']);
    }

    /**
     * Scope a query to only include expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->whereIn('status', ['canceled', 'past_due', 'unpaid']);
    }

    /**
     * Scope a query to only include trialing subscriptions.
     */
    public function scopeTrialing($query)
    {
        return $query->where('status', 'trialing');
    }

    /**
     * Scope a query to only include paused subscriptions.
     */
    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    /**
     * Scope a query to only include canceled subscriptions.
     */
    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    /**
     * Scope a query to only include subscriptions expiring soon.
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('status', 'active')
            ->whereNotNull('current_period_end')
            ->whereDate('current_period_end', '<=', now()->addDays($days))
            ->whereDate('current_period_end', '>=', now());
    }

    /**
     * Scope a query to only include subscriptions by billing cycle.
     */
    public function scopeByBillingCycle($query, $cycle)
    {
        return $query->where('billing_cycle', $cycle);
    }

    /**
     * Scope a query to only include subscriptions with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include subscriptions created in a date range.
     */
    public function scopeCreatedBetween($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Scope a query to order by current period end.
     */
    public function scopeOrderByPeriodEnd($query, $direction = 'asc')
    {
        return $query->orderBy('current_period_end', $direction);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return self::$statusLabels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute()
    {
        return self::$statusColors[$this->status] ?? 'gray';
    }

    /**
     * Get the formatted amount with currency.
     */
    public function getFormattedAmountAttribute()
    {
        $currency = $this->currency ?? 'USD';
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'JPY' => '¥',
        ];
        
        $symbol = $symbols[$currency] ?? '$';
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Get the days left until the current period ends.
     */
    public function getDaysLeftAttribute()
    {
        if (!$this->current_period_end) {
            return null;
        }

        $days = now()->diffInDays($this->current_period_end, false);
        return (int) floor($days);
    }

    /**
     * Check if the subscription is expiring soon.
     */
    public function getIsExpiringSoonAttribute()
    {
        if (!$this->current_period_end) {
            return false;
        }

        $daysLeft = $this->days_left;
        return $daysLeft !== null && $daysLeft <= 7 && $daysLeft >= 0;
    }

    /**
     * Get the billing cycle label.
     */
    public function getBillingCycleLabelAttribute()
    {
        return ucfirst($this->billing_cycle);
    }

    /**
     * Get the period duration in days.
     */
    public function getPeriodDurationAttribute()
    {
        if (!$this->current_period_start || !$this->current_period_end) {
            return null;
        }

        return $this->current_period_start->diffInDays($this->current_period_end);
    }

    /**
     * Get the subscription age in days.
     */
    public function getAgeInDaysAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get the formatted current period.
     */
    public function getFormattedPeriodAttribute()
    {
        if (!$this->current_period_start || !$this->current_period_end) {
            return null;
        }

        return $this->current_period_start->format('M d, Y') . ' - ' . 
               $this->current_period_end->format('M d, Y');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if the subscription is active.
     */
    public function isActive()
    {
        return in_array($this->status, ['active', 'trialing']);
    }

    /**
     * Check if the subscription is on trial.
     */
    public function isOnTrial()
    {
        return $this->status === 'trialing' && $this->trial_ends_at?->isFuture();
    }

    /**
     * Check if the subscription is expired.
     */
    public function isExpired()
    {
        if ($this->ends_at && $this->ends_at->isPast()) {
            return true;
        }

        if ($this->current_period_end && $this->current_period_end->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Check if the subscription is canceled.
     */
    public function isCanceled()
    {
        return $this->status === 'canceled' || $this->canceled_at !== null;
    }

    /**
     * Check if the subscription is paused.
     */
    public function isPaused()
    {
        return $this->status === 'paused';
    }

    /**
     * Check if the subscription is past due.
     */
    public function isPastDue()
    {
        return $this->status === 'past_due';
    }

    /**
     * Check if the subscription is unpaid.
     */
    public function isUnpaid()
    {
        return $this->status === 'unpaid';
    }

    /**
     * Check if the subscription is incomplete.
     */
    public function isIncomplete()
    {
        return $this->status === 'incomplete';
    }

    /**
     * Get the remaining trial days.
     */
    public function getTrialDaysRemaining()
    {
        if (!$this->trial_ends_at) {
            return 0;
        }

        if ($this->trial_ends_at->isPast()) {
            return 0;
        }

        return (int) floor(now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Check if subscription is in a cancellable state.
     */
    public function isCancellable()
    {
        return in_array($this->status, ['active', 'trialing', 'past_due']);
    }

    /**
     * Check if subscription is resumable.
     */
    public function isResumable()
    {
        return $this->status === 'canceled' && 
               (!$this->ends_at || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription is pausable.
     */
    public function isPausable()
    {
        return in_array($this->status, ['active', 'trialing']);
    }

    /**
     * Get the Stripe price ID for the current billing cycle.
     */
    public function getStripePriceId()
    {
        return $this->billing_cycle === 'monthly' 
            ? $this->plan->stripe_price_id_monthly 
            : $this->plan->stripe_price_id_yearly;
    }

    /**
     * Calculate the total amount paid.
     */
    public function getTotalPaid()
    {
        return $this->payments()
            ->where('status', 'succeeded')
            ->sum('amount');
    }

    /**
     * Get the next billing date.
     */
    public function getNextBillingDate()
    {
        return $this->current_period_end;
    }

    /**
     * Check if the subscription has usage metrics for a specific period.
     */
    public function hasUsageForPeriod($metricName, $start, $end)
    {
        return $this->usageMetrics()
            ->where('metric_name', $metricName)
            ->whereBetween('period_start', [$start, $end])
            ->exists();
    }

    /**
     * Get usage for a specific metric.
     */
    public function getUsageForMetric($metricName, $start = null, $end = null)
    {
        $query = $this->usageMetrics()->where('metric_name', $metricName);
        
        if ($start && $end) {
            $query->whereBetween('period_start', [$start, $end]);
        }

        return $query->sum('value');
    }

    /**
     * Get the subscription status badge HTML.
     */
    public function getStatusBadge()
    {
        $colors = [
            'green' => 'bg-green-100 text-green-800',
            'blue' => 'bg-blue-100 text-blue-800',
            'orange' => 'bg-orange-100 text-orange-800',
            'red' => 'bg-red-100 text-red-800',
            'gray' => 'bg-gray-100 text-gray-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
        ];

        $color = $colors[$this->status_color] ?? $colors['gray'];
        $label = $this->status_label;

        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$color}'>{$label}</span>";
    }

    /**
     * Get the expiration warning message.
     */
    public function getExpirationWarning()
    {
        if ($this->isExpired()) {
            return 'This subscription has expired.';
        }

        if ($this->is_expiring_soon) {
            $days = $this->days_left;
            return "This subscription will expire in {$days} days.";
        }

        return null;
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get all possible statuses.
     */
    public static function getStatuses()
    {
        return self::$statusLabels;
    }

    /**
     * Get all possible billing cycles.
     */
    public static function getBillingCycles()
    {
        return ['monthly', 'yearly', 'quarterly'];
    }

    /**
     * Get all possible status colors.
     */
    public static function getStatusColors()
    {
        return self::$statusColors;
    }

    /**
     * Create a new subscription with proper defaults.
     */
    public static function createWithDefaults(array $data)
    {
        $defaults = [
            'currency' => 'USD',
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'current_period_start' => now(),
        ];

        $data = array_merge($defaults, $data);

        // Calculate period end if not provided
        if (!isset($data['current_period_end'])) {
            $periodMap = [
                'monthly' => 'addMonth',
                'yearly' => 'addYear',
                'quarterly' => 'addMonths',
            ];

            $method = $periodMap[$data['billing_cycle']] ?? 'addMonth';
            $data['current_period_end'] = $data['current_period_start']->$method();
        }

        return self::create($data);
    }

    // ==================== BOOT METHODS ====================

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-calculate amount if not provided
        static::creating(function ($subscription) {
            if (!$subscription->amount && $subscription->plan) {
                $subscription->amount = $subscription->billing_cycle === 'monthly' 
                    ? $subscription->plan->price_monthly 
                    : ($subscription->billing_cycle === 'yearly' 
                        ? ($subscription->plan->price_yearly ?? $subscription->plan->price_monthly * 12) 
                        : $subscription->plan->price_monthly * 3);
            }

            // Set currency from plan if not provided
            if (!$subscription->currency && $subscription->plan) {
                $subscription->currency = $subscription->plan->currency ?? 'USD';
            }
        });

        // Update status when period ends
        static::updating(function ($subscription) {
            if ($subscription->current_period_end && 
                $subscription->current_period_end->isPast() && 
                $subscription->status === 'active') {
                $subscription->status = 'past_due';
            }
        });
    }

    // ==================== VALIDATION RULES ====================

    /**
     * Get the validation rules for creating/updating.
     */
    public static function getValidationRules($id = null)
    {
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', 'in:active,trialing,past_due,canceled,unpaid,incomplete,paused'],
            'billing_cycle' => ['required', 'in:monthly,yearly,quarterly'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'trial_ends_at' => ['nullable', 'date'],
            'current_period_start' => ['nullable', 'date'],
            'current_period_end' => ['nullable', 'date', 'after:current_period_start'],
            'stripe_subscription_id' => ['nullable', 'string', 'max:255'],
            'stripe_customer_id' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];

        if ($id) {
            $rules['user_id'][] = 'unique:subscriptions,user_id,' . $id . ',id,status,active';
        }

        return $rules;
    }
}