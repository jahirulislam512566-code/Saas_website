<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    use EnumTrait;

    case DRAFT = 'draft';
    case SENT = 'sent';
    case VIEWED = 'viewed';
    case PENDING = 'pending';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case DISPUTED = 'disputed';
    case COLLECTION = 'collection';

    /**
     * Get descriptions for each status
     */
    protected static function descriptions(): array
    {
        return [
            'draft' => 'Invoice is in draft mode',
            'sent' => 'Invoice has been sent to customer',
            'viewed' => 'Invoice has been viewed by customer',
            'pending' => 'Invoice is pending payment',
            'partially_paid' => 'Invoice has been partially paid',
            'paid' => 'Invoice has been fully paid',
            'overdue' => 'Invoice is overdue',
            'cancelled' => 'Invoice has been cancelled',
            'refunded' => 'Invoice has been refunded',
            'disputed' => 'Invoice is under dispute',
            'collection' => 'Invoice is in collection',
        ];
    }

    /**
     * Get colors for each status
     */
    protected static function colors(): array
    {
        return [
            'draft' => '#6c757d',       // Secondary
            'sent' => '#007bff',        // Primary
            'viewed' => '#17a2b8',      // Info
            'pending' => '#ffc107',     // Yellow
            'partially_paid' => '#fd7e14', // Orange
            'paid' => '#28a745',        // Success
            'overdue' => '#dc3545',     // Danger
            'cancelled' => '#6c757d',   // Secondary
            'refunded' => '#6c757d',    // Secondary
            'disputed' => '#dc3545',    // Danger
            'collection' => '#dc3545',  // Danger
        ];
    }

    /**
     * Get icons for each status
     */
    protected static function icons(): array
    {
        return [
            'draft' => 'fa-file-alt',
            'sent' => 'fa-paper-plane',
            'viewed' => 'fa-eye',
            'pending' => 'fa-clock',
            'partially_paid' => 'fa-money-bill-wave',
            'paid' => 'fa-check-circle',
            'overdue' => 'fa-exclamation-triangle',
            'cancelled' => 'fa-times-circle',
            'refunded' => 'fa-undo',
            'disputed' => 'fa-gavel',
            'collection' => 'fa-phone',
        ];
    }

    /**
     * Check if invoice needs action
     */
    public function needsAction(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::OVERDUE,
            self::DISPUTED,
            self::COLLECTION,
        ]);
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return in_array($this, [self::PAID, self::PARTIALLY_PAID]);
    }

    /**
     * Check if invoice is final
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::PAID,
            self::CANCELLED,
            self::REFUNDED,
        ]);
    }
}