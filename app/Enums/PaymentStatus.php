<?php

namespace App\Enums;

enum PaymentStatus: string
{
    use EnumTrait;

    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case PARTIALLY_REFUNDED = 'partially_refunded';
    case CANCELLED = 'cancelled';
    case AUTHORIZED = 'authorized';
    case CAPTURED = 'captured';

    /**
     * Get descriptions for each status
     */
    protected static function descriptions(): array
    {
        return [
            'pending' => 'Payment is pending processing',
            'processing' => 'Payment is currently being processed',
            'completed' => 'Payment has been successfully completed',
            'failed' => 'Payment has failed',
            'refunded' => 'Payment has been fully refunded',
            'partially_refunded' => 'Payment has been partially refunded',
            'cancelled' => 'Payment has been cancelled',
            'authorized' => 'Payment has been authorized but not captured',
            'captured' => 'Payment has been captured',
        ];
    }

    /**
     * Get colors for each status
     */
    protected static function colors(): array
    {
        return [
            'pending' => '#ffc107',      // Yellow
            'processing' => '#17a2b8',   // Info
            'completed' => '#28a745',    // Success
            'failed' => '#dc3545',       // Danger
            'refunded' => '#6c757d',     // Secondary
            'partially_refunded' => '#fd7e14', // Orange
            'cancelled' => '#dc3545',    // Danger
            'authorized' => '#6f42c1',   // Purple
            'captured' => '#28a745',     // Success
        ];
    }

    /**
     * Get icons for each status
     */
    protected static function icons(): array
    {
        return [
            'pending' => 'fa-clock',
            'processing' => 'fa-spinner fa-spin',
            'completed' => 'fa-check-circle',
            'failed' => 'fa-times-circle',
            'refunded' => 'fa-undo',
            'partially_refunded' => 'fa-undo-alt',
            'cancelled' => 'fa-ban',
            'authorized' => 'fa-lock',
            'captured' => 'fa-check-double',
        ];
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return in_array($this, [self::COMPLETED, self::CAPTURED]);
    }

    /**
     * Check if payment can be refunded
     */
    public function isRefundable(): bool
    {
        return in_array($this, [self::COMPLETED, self::CAPTURED, self::AUTHORIZED]);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return in_array($this, [self::PENDING, self::PROCESSING]);
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return in_array($this, [self::FAILED, self::CANCELLED]);
    }
}