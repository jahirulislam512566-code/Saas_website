<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Team;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subdomain',
        'domain',
        'database',
        'email',
        'phone',
        'logo',
        'favicon',
        'timezone',
        'currency',
        'currency_symbol',
        'language',
        'settings',
        'preferences',
        'is_active',
        'trial_ends_at',
        'subscription_ends_at',
        'status',
    ];

    protected $casts = [
        'settings' => 'array',
        'preferences' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at > now();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_ends_at && $this->subscription_ends_at > now();
    }
}