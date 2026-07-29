<?php

namespace App\Enums;

enum Priority: string
{
    use EnumTrait;

    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';
    case CRITICAL = 'critical';

    /**
     * Get descriptions for each priority
     */
    protected static function descriptions(): array
    {
        return [
            'low' => 'Low priority - Can be addressed when time permits',
            'medium' => 'Medium priority - Should be addressed soon',
            'high' => 'High priority - Needs prompt attention',
            'urgent' => 'Urgent - Needs immediate attention',
            'critical' => 'Critical - System is down or severely impacted',
        ];
    }

    /**
     * Get colors for each priority
     */
    protected static function colors(): array
    {
        return [
            'low' => '#28a745',    // Success
            'medium' => '#17a2b8', // Info
            'high' => '#ffc107',   // Yellow
            'urgent' => '#fd7e14', // Orange
            'critical' => '#dc3545', // Danger
        ];
    }

    /**
     * Get icons for each priority
     */
    protected static function icons(): array
    {
        return [
            'low' => 'fa-arrow-down',
            'medium' => 'fa-arrow-right',
            'high' => 'fa-arrow-up',
            'urgent' => 'fa-exclamation',
            'critical' => 'fa-exclamation-triangle',
        ];
    }

    /**
     * Get SLA response time in hours
     */
    public function slaHours(): int
    {
        return match ($this) {
            self::LOW => 48,
            self::MEDIUM => 24,
            self::HIGH => 8,
            self::URGENT => 4,
            self::CRITICAL => 1,
        };
    }

    /**
     * Get SLA resolution time in hours
     */
    public function slaResolutionHours(): int
    {
        return match ($this) {
            self::LOW => 168,  // 7 days
            self::MEDIUM => 72, // 3 days
            self::HIGH => 24,   // 1 day
            self::URGENT => 8,
            self::CRITICAL => 4,
        };
    }

    /**
     * Get severity level
     */
    public function severity(): int
    {
        return match ($this) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
            self::URGENT => 4,
            self::CRITICAL => 5,
        };
    }
}