<?php

namespace App\Enums;

enum TicketStatus: string
{
    use EnumTrait;

    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case WAITING = 'waiting';
    case ON_HOLD = 'on_hold';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case ESCALATED = 'escalated';
    case REOPENED = 'reopened';

    /**
     * Get descriptions for each status
     */
    protected static function descriptions(): array
    {
        return [
            'open' => 'Ticket is open and awaiting response',
            'in_progress' => 'Ticket is currently being worked on',
            'waiting' => 'Ticket is waiting for customer response',
            'on_hold' => 'Ticket is temporarily on hold',
            'resolved' => 'Ticket has been resolved',
            'closed' => 'Ticket has been closed',
            'escalated' => 'Ticket has been escalated',
            'reopened' => 'Ticket has been reopened',
        ];
    }

    /**
     * Get colors for each status
     */
    protected static function colors(): array
    {
        return [
            'open' => '#28a745',      // Success
            'in_progress' => '#17a2b8', // Info
            'waiting' => '#ffc107',    // Yellow
            'on_hold' => '#fd7e14',    // Orange
            'resolved' => '#6c757d',   // Secondary
            'closed' => '#6c757d',     // Secondary
            'escalated' => '#dc3545',  // Danger
            'reopened' => '#dc3545',   // Danger
        ];
    }

    /**
     * Get icons for each status
     */
    protected static function icons(): array
    {
        return [
            'open' => 'fa-ticket-alt',
            'in_progress' => 'fa-spinner fa-spin',
            'waiting' => 'fa-clock',
            'on_hold' => 'fa-pause',
            'resolved' => 'fa-check-circle',
            'closed' => 'fa-times-circle',
            'escalated' => 'fa-arrow-up',
            'reopened' => 'fa-undo',
        ];
    }

    /**
     * Check if ticket is active
     */
    public function isActive(): bool
    {
        return in_array($this, [
            self::OPEN,
            self::IN_PROGRESS,
            self::WAITING,
            self::ON_HOLD,
            self::ESCALATED,
            self::REOPENED,
        ]);
    }

    /**
     * Check if ticket is resolved
     */
    public function isResolved(): bool
    {
        return in_array($this, [self::RESOLVED, self::CLOSED]);
    }

    /**
     * Get priority levels
     */
    public function priority(): string
    {
        return match ($this) {
            self::ESCALATED => 'critical',
            self::OPEN => 'high',
            self::IN_PROGRESS => 'medium',
            default => 'low',
        };
    }
}