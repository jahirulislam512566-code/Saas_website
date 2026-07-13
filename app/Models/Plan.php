<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'stripe_price_id_monthly',
        'stripe_price_id_yearly',
        'features',
        'limits',
        'trial_days',
        'is_active',
        'is_featured',
        'sort_order',
        'metadata',
        'setup_fee',
        'max_users',
        'max_projects',
        'storage_limit',
        'support_level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'features' => 'array',
        'limits' => 'array',
        'metadata' => 'array',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'trial_days' => 'integer',
        'max_users' => 'integer',
        'max_projects' => 'integer',
        'storage_limit' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array<string>
     */
    protected $appends = [
        'price_monthly_formatted',
        'price_yearly_formatted',
        'setup_fee_formatted',
        'savings_percentage',
        'feature_list',
        'limit_list',
        'has_trial',
        'popularity_score',
    ];

    /**
     * The billing cycles available.
     *
     * @var array<string>
     */
    protected static $billingCycles = ['monthly', 'yearly', 'quarterly'];

    /**
     * The support levels available.
     *
     * @var array<string>
     */
    protected static $supportLevels = [
        'basic' => 'Basic Support',
        'standard' => 'Standard Support',
        'priority' => 'Priority Support',
        'dedicated' => 'Dedicated Support',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the subscriptions for the plan.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscriptions for the plan.
     */
    public function activeSubscriptions()
    {
        return $this->subscriptions()->whereIn('status', ['active', 'trialing']);
    }

    /**
     * Get the users subscribed to this plan.
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Subscription::class, 'plan_id', 'id', 'id', 'user_id')
            ->whereIn('subscriptions.status', ['active', 'trialing']);
    }

    /**
     * Get the activities for the plan.
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive plans.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include featured plans.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include plans with trial.
     */
    public function scopeHasTrial($query)
    {
        return $query->where('trial_days', '>', 0);
    }

    /**
     * Scope a query to only include plans without trial.
     */
    public function scopeNoTrial($query)
    {
        return $query->where('trial_days', 0);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price_monthly');
    }

    /**
     * Scope a query to only include plans with a specific billing cycle.
     */
    public function scopeWithBillingCycle($query, $cycle)
    {
        return $query->where(function ($q) use ($cycle) {
            if ($cycle === 'monthly') {
                $q->whereNotNull('price_monthly');
            } elseif ($cycle === 'yearly') {
                $q->whereNotNull('price_yearly');
            }
        });
    }

    /**
     * Scope a query to only include plans within a price range.
     */
    public function scopePriceBetween($query, $min, $max, $cycle = 'monthly')
    {
        $column = $cycle === 'monthly' ? 'price_monthly' : 'price_yearly';
        return $query->whereBetween($column, [$min, $max]);
    }

    /**
     * Scope a query to only include plans with a specific feature.
     */
    public function scopeWithFeature($query, $feature)
    {
        return $query->where('features', 'LIKE', "%{$feature}%");
    }

    /**
     * Scope a query to only include plans with support level.
     */
    public function scopeWithSupportLevel($query, $level)
    {
        return $query->where('support_level', $level);
    }

    /**
     * Scope a query to only include plans with max users.
     */
    public function scopeWithMaxUsers($query, $operator, $value)
    {
        return $query->where('max_users', $operator, $value);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get the formatted monthly price.
     */
    public function getPriceMonthlyFormattedAttribute()
    {
        if ($this->price_monthly === null) {
            return 'N/A';
        }
        return $this->currency . ' ' . number_format($this->price_monthly, 2);
    }

    /**
     * Get the formatted yearly price.
     */
    public function getPriceYearlyFormattedAttribute()
    {
        if ($this->price_yearly === null) {
            return 'N/A';
        }
        return $this->currency . ' ' . number_format($this->price_yearly, 2);
    }

    /**
     * Get the formatted setup fee.
     */
    public function getSetupFeeFormattedAttribute()
    {
        if ($this->setup_fee === null || $this->setup_fee <= 0) {
            return 'Free';
        }
        return $this->currency . ' ' . number_format($this->setup_fee, 2);
    }

    /**
     * Get the savings percentage when paying yearly.
     */
    public function getSavingsPercentageAttribute()
    {
        if (!$this->price_yearly || !$this->price_monthly || $this->price_monthly <= 0) {
            return 0;
        }

        $monthlyTotal = $this->price_monthly * 12;
        $savings = $monthlyTotal - $this->price_yearly;

        if ($savings <= 0) {
            return 0;
        }

        return round(($savings / $monthlyTotal) * 100);
    }

    /**
     * Get the feature list as a string.
     */
    public function getFeatureListAttribute()
    {
        if (!$this->features || !is_array($this->features)) {
            return '';
        }
        return implode(', ', $this->features);
    }

    /**
     * Get the limit list as a string.
     */
    public function getLimitListAttribute()
    {
        if (!$this->limits || !is_array($this->limits)) {
            return '';
        }
        return implode(', ', $this->limits);
    }

    /**
     * Check if the plan has a trial.
     */
    public function getHasTrialAttribute()
    {
        return $this->trial_days > 0;
    }

    /**
     * Get the popularity score based on subscribers.
     */
    public function getPopularityScoreAttribute()
    {
        $totalSubscribers = $this->activeSubscriptions()->count();
        
        if ($totalSubscribers === 0) {
            return 'No subscribers';
        } elseif ($totalSubscribers < 10) {
            return 'Low';
        } elseif ($totalSubscribers < 50) {
            return 'Medium';
        } elseif ($totalSubscribers < 100) {
            return 'High';
        } else {
            return 'Very High';
        }
    }

    /**
     * Get the annual equivalent price.
     */
    public function getAnnualEquivalentAttribute()
    {
        if (!$this->price_monthly) {
            return null;
        }
        return $this->price_monthly * 12;
    }

    /**
     * Get the monthly equivalent price (for yearly plans).
     */
    public function getMonthlyEquivalentAttribute()
    {
        if (!$this->price_yearly) {
            return null;
        }
        return $this->price_yearly / 12;
    }

    /**
     * Get the savings amount when paying yearly.
     */
    public function getSavingsAmountAttribute()
    {
        if (!$this->price_yearly || !$this->price_monthly) {
            return 0;
        }

        $monthlyTotal = $this->price_monthly * 12;
        return max(0, $monthlyTotal - $this->price_yearly);
    }

    /**
     * Get the trial days label.
     */
    public function getTrialLabelAttribute()
    {
        if ($this->trial_days === 0) {
            return 'No trial';
        }
        return $this->trial_days . ' ' . Str::plural('day', $this->trial_days);
    }

    /**
     * Get the support level label.
     */
    public function getSupportLevelLabelAttribute()
    {
        return self::$supportLevels[$this->support_level] ?? ucfirst($this->support_level ?? 'basic');
    }

    /**
     * Set the features attribute.
     */
    public function setFeaturesAttribute($value)
    {
        if (is_string($value)) {
            $value = array_filter(array_map('trim', explode(',', $value)));
        }
        $this->attributes['features'] = json_encode($value ?? []);
    }

    /**
     * Set the limits attribute.
     */
    public function setLimitsAttribute($value)
    {
        if (is_string($value)) {
            $value = array_filter(array_map('trim', explode(',', $value)));
        }
        $this->attributes['limits'] = json_encode($value ?? []);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the Stripe price ID for a given billing cycle.
     */
    public function getStripePriceId($billingCycle)
    {
        if ($billingCycle === 'monthly') {
            return $this->stripe_price_id_monthly;
        } elseif ($billingCycle === 'yearly') {
            return $this->stripe_price_id_yearly;
        }
        return null;
    }

    /**
     * Get the price for a specific billing cycle.
     */
    public function getPriceForCycle($billingCycle)
    {
        if ($billingCycle === 'monthly') {
            return $this->price_monthly;
        } elseif ($billingCycle === 'yearly') {
            return $this->price_yearly ?? ($this->price_monthly * 12);
        } elseif ($billingCycle === 'quarterly') {
            return $this->price_monthly * 3;
        }
        return null;
    }

    /**
     * Check if the plan has a specific feature.
     */
    public function hasFeature($feature)
    {
        if (!$this->features) {
            return false;
        }
        return in_array($feature, $this->features);
    }

    /**
     * Check if the plan has a specific limit.
     */
    public function hasLimit($limit)
    {
        if (!$this->limits) {
            return false;
        }
        return in_array($limit, $this->limits);
    }

    /**
     * Get the total number of subscribers.
     */
    public function getSubscriberCount()
    {
        return $this->activeSubscriptions()->count();
    }

    /**
     * Get the monthly revenue from this plan.
     */
    public function getMonthlyRevenue()
    {
        return $this->activeSubscriptions()
            ->where('billing_cycle', 'monthly')
            ->sum('amount');
    }

    /**
     * Get the yearly revenue from this plan.
     */
    public function getYearlyRevenue()
    {
        return $this->activeSubscriptions()
            ->where('billing_cycle', 'yearly')
            ->sum('amount');
    }

    /**
     * Get the total revenue from this plan.
     */
    public function getTotalRevenue()
    {
        return $this->activeSubscriptions()->sum('amount');
    }

    /**
     * Check if the plan is available for purchase.
     */
    public function isAvailable()
    {
        return $this->is_active && ($this->price_monthly !== null || $this->price_yearly !== null);
    }

    /**
     * Get the plan pricing summary.
     */
    public function getPricingSummary()
    {
        $summary = [
            'currency' => $this->currency,
            'monthly' => $this->price_monthly,
            'yearly' => $this->price_yearly,
            'monthly_formatted' => $this->price_monthly_formatted,
            'yearly_formatted' => $this->price_yearly_formatted,
            'savings_percentage' => $this->savings_percentage,
        ];

        return $summary;
    }

    /**
     * Get the next billing date for a new subscription.
     */
    public function getNextBillingDate($billingCycle)
    {
        $periodMap = [
            'monthly' => 'addMonth',
            'yearly' => 'addYear',
            'quarterly' => 'addMonths',
        ];

        $method = $periodMap[$billingCycle] ?? 'addMonth';
        $amount = $billingCycle === 'quarterly' ? 3 : 1;

        return now()->$method($amount);
    }

    /**
     * Calculate the prorated price for a partial period.
     */
    public function calculateProratedPrice($billingCycle, $daysUsed, $totalDays)
    {
        $fullPrice = $this->getPriceForCycle($billingCycle);
        if (!$fullPrice) {
            return 0;
        }

        $remainingDays = $totalDays - $daysUsed;
        return round(($fullPrice / $totalDays) * $remainingDays, 2);
    }

    /**
     * Get the plan card data for display.
     */
    public function getCardData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price_monthly' => $this->price_monthly,
            'price_yearly' => $this->price_yearly,
            'price_monthly_formatted' => $this->price_monthly_formatted,
            'price_yearly_formatted' => $this->price_yearly_formatted,
            'currency' => $this->currency,
            'features' => $this->features,
            'limits' => $this->limits,
            'trial_days' => $this->trial_days,
            'trial_label' => $this->trial_label,
            'is_featured' => $this->is_featured,
            'savings_percentage' => $this->savings_percentage,
            'subscriber_count' => $this->getSubscriberCount(),
            'support_level' => $this->support_level_label,
            'has_trial' => $this->has_trial,
        ];
    }

    /**
     * Get the billing cycles available for this plan.
     */
    public function getAvailableBillingCycles()
    {
        $cycles = [];

        if ($this->price_monthly !== null && $this->stripe_price_id_monthly) {
            $cycles[] = 'monthly';
        }

        if ($this->price_yearly !== null && $this->stripe_price_id_yearly) {
            $cycles[] = 'yearly';
        }

        return $cycles;
    }

    /**
     * Get the features grouped by category.
     */
    public function getFeaturesGrouped()
    {
        if (!$this->features) {
            return [];
        }

        $grouped = [];
        foreach ($this->features as $feature) {
            $parts = explode(':', $feature);
            if (count($parts) === 2) {
                $grouped[$parts[0]][] = $parts[1];
            } else {
                $grouped['general'][] = $feature;
            }
        }

        return $grouped;
    }

    /**
     * Check if plan has setup fee.
     */
    public function hasSetupFee()
    {
        return $this->setup_fee && $this->setup_fee > 0;
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get all billing cycles.
     */
    public static function getBillingCycles()
    {
        return self::$billingCycles;
    }

    /**
     * Get all support levels.
     */
    public static function getSupportLevels()
    {
        return self::$supportLevels;
    }

    /**
     * Get the default plan.
     */
    public static function getDefault()
    {
        return self::active()->ordered()->first();
    }

    /**
     * Get the cheapest plan.
     */
    public static function getCheapest($billingCycle = 'monthly')
    {
        $column = $billingCycle === 'monthly' ? 'price_monthly' : 'price_yearly';
        return self::active()
            ->whereNotNull($column)
            ->orderBy($column)
            ->first();
    }

    /**
     * Get the most popular plan.
     */
    public static function getMostPopular()
    {
        return self::active()
            ->withCount('subscriptions')
            ->orderBy('subscriptions_count', 'desc')
            ->first();
    }

    /**
     * Get plans for pricing page.
     */
    public static function getPricingPlans($includeFeatured = true)
    {
        $query = self::active()->ordered();

        if ($includeFeatured) {
            $query->orderByRaw('is_featured DESC');
        }

        return $query->get();
    }

    /**
     * Get validation rules.
     */
    public static function getValidationRules($id = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:plans,slug,' . $id],
            'description' => ['nullable', 'string'],
            'price_monthly' => ['nullable', 'numeric', 'min:0'],
            'price_yearly' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'stripe_price_id_monthly' => ['nullable', 'string', 'max:255'],
            'stripe_price_id_yearly' => ['nullable', 'string', 'max:255'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'limits' => ['nullable', 'array'],
            'limits.*' => ['nullable', 'string', 'max:255'],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'setup_fee' => ['nullable', 'numeric', 'min:0'],
            'max_users' => ['nullable', 'integer', 'min:0'],
            'max_projects' => ['nullable', 'integer', 'min:0'],
            'storage_limit' => ['nullable', 'integer', 'min:0'],
            'support_level' => ['nullable', 'string', 'in:basic,standard,priority,dedicated'],
        ];

        // Ensure at least one price is set
        $rules['price_monthly'][] = function ($attribute, $value, $fail) use ($request) {
            if (!$value && !request('price_yearly')) {
                $fail('At least one price (monthly or yearly) must be set.');
            }
        };

        return $rules;
    }

    // ==================== BOOT METHODS ====================

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-generate slug
        static::creating(function ($plan) {
            if (!$plan->slug) {
                $plan->slug = Str::slug($plan->name);
            }

            // Ensure unique slug
            $originalSlug = $plan->slug;
            $count = 1;
            while (self::where('slug', $plan->slug)->where('id', '!=', $plan->id)->exists()) {
                $plan->slug = $originalSlug . '-' . $count++;
            }

            // Set default sort order
            if ($plan->sort_order === null) {
                $plan->sort_order = self::max('sort_order') + 1;
            }

            // Set default currency
            if (!$plan->currency) {
                $plan->currency = 'USD';
            }

            // Set default support level
            if (!$plan->support_level) {
                $plan->support_level = 'basic';
            }
        });
    }
}