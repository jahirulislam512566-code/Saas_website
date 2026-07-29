<?php

namespace App\Enums;

enum CustomerStatus: string
{
    use EnumTrait;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case DEACTIVATED = 'deactivated';
    case PENDING = 'pending';
    case ARCHIVED = 'archived';
    case BLACKLISTED = 'blacklisted';
    case VIP = 'vip';

    /**
     * Get descriptions for each status
     */
    protected static function descriptions(): array
    {
        return [
            'active' => 'Customer is active and can use services',
            'inactive' => 'Customer is inactive',
            'suspended' => 'Customer has been temporarily suspended',
            'deactivated' => 'Customer has been deactivated',
            'pending' => 'Customer account is pending approval',
            'archived' => 'Customer has been archived',
            'blacklisted' => 'Customer has been blacklisted',
            'vip' => 'Customer is a VIP',
        ];
    }

    /**
     * Get colors for each status
     */
    protected static function colors(): array
    {
        return [
            'active' => '#28a745',    // Success
            'inactive' => '#6c757d',  // Secondary
            'suspended' => '#ffc107', // Yellow
            'deactivated' => '#dc3545', // Danger
            'pending' => '#17a2b8',   // Info
            'archived' => '#6c757d',  // Secondary
            'blacklisted' => '#dc3545', // Danger
            'vip' => '#ffc107',       // Yellow
        ];
    }

    /**
     * Get icons for each status
     */
    protected static function icons(): array
    {
        return [
            'active' => 'fa-user-check',
            'inactive' => 'fa-user-times',
            'suspended' => 'fa-user-slash',
            'deactivated' => 'fa-user-minus',
            'pending' => 'fa-user-clock',
            'archived' => 'fa-archive',
            'blacklisted' => 'fa-ban',
            'vip' => 'fa-crown',
        ];
    }

    /**
     * Check if customer can access services
     */
    public function canAccess(): bool
    {
        return in_array($this, [self::ACTIVE, self::VIP]);
    }

    /**
     * Check if customer needs review
     */
    public function needsReview(): bool
    {
        return in_array($this, [self::SUSPENDED, self::BLACKLISTED]);
    }
}