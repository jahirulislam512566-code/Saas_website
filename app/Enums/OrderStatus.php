<?php

namespace App\Enums;

enum OrderStatus: string
{
    use EnumTrait;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case CONFIRMED = 'confirmed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case ON_HOLD = 'on_hold';
    case FAILED = 'failed';

    /**
     * Get descriptions for each status
     */
    protected static function descriptions(): array
    {
        return [
            'draft' => 'Order is in draft mode',
            'pending' => 'Order is pending processing',
            'processing' => 'Order is currently being processed',
            'confirmed' => 'Order has been confirmed',
            'shipped' => 'Order has been shipped',
            'delivered' => 'Order has been delivered',
            'completed' => 'Order has been completed',
            'cancelled' => 'Order has been cancelled',
            'refunded' => 'Order has been refunded',
            'on_hold' => 'Order is temporarily on hold',
            'failed' => 'Order processing has failed',
        ];
    }

    /**
     * Get colors for each status
     */
    protected static function colors(): array
    {
        return [
            'draft' => '#6c757d',     // Secondary
            'pending' => '#ffc107',   // Yellow
            'processing' => '#17a2b8', // Info
            'confirmed' => '#28a745',  // Success
            'shipped' => '#007bff',    // Primary
            'delivered' => '#20c997',  // Teal
            'completed' => '#28a745',  // Success
            'cancelled' => '#dc3545',  // Danger
            'refunded' => '#6c757d',   // Secondary
            'on_hold' => '#fd7e14',    // Orange
            'failed' => '#dc3545',     // Danger
        ];
    }

    /**
     * Get icons for each status
     */
    protected static function icons(): array
    {
        return [
            'draft' => 'fa-pencil-alt',
            'pending' => 'fa-clock',
            'processing' => 'fa-spinner fa-spin',
            'confirmed' => 'fa-check',
            'shipped' => 'fa-truck',
            'delivered' => 'fa-home',
            'completed' => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
            'refunded' => 'fa-undo',
            'on_hold' => 'fa-pause',
            'failed' => 'fa-exclamation-triangle',
        ];
    }

    /**
     * Check if order is active
     */
    public function isActive(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::PROCESSING,
            self::CONFIRMED,
            self::SHIPPED,
            self::DELIVERED,
        ]);
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this, [self::COMPLETED, self::DELIVERED]);
    }

    /**
     * Check if order can be cancelled
     */
    public function isCancellable(): bool
    {
        return in_array($this, [
            self::DRAFT,
            self::PENDING,
            self::PROCESSING,
            self::CONFIRMED,
        ]);
    }

    /**
     * Get next status for workflow
     */
    public function next(): ?self
    {
        return match ($this) {
            self::DRAFT => self::PENDING,
            self::PENDING => self::PROCESSING,
            self::PROCESSING => self::CONFIRMED,
            self::CONFIRMED => self::SHIPPED,
            self::SHIPPED => self::DELIVERED,
            self::DELIVERED => self::COMPLETED,
            default => null,
        };
    }
}