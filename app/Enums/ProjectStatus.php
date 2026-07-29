<?php

namespace App\Enums;

enum ProjectStatus: string
{
    use EnumTrait;

    case PLANNING = 'planning';
    case IN_PROGRESS = 'in_progress';
    case REVIEW = 'review';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case ON_HOLD = 'on_hold';
    case ARCHIVED = 'archived';
    case PENDING_APPROVAL = 'pending_approval';
    case TESTING = 'testing';
    case DEPLOYED = 'deployed';

    /**
     * Get descriptions for each status
     */
    protected static function descriptions(): array
    {
        return [
            'planning' => 'Project is in planning phase',
            'in_progress' => 'Project is currently in progress',
            'review' => 'Project is under review',
            'completed' => 'Project has been completed',
            'cancelled' => 'Project has been cancelled',
            'on_hold' => 'Project is temporarily on hold',
            'archived' => 'Project has been archived',
            'pending_approval' => 'Project is pending approval',
            'testing' => 'Project is in testing phase',
            'deployed' => 'Project has been deployed',
        ];
    }

    /**
     * Get colors for each status
     */
    protected static function colors(): array
    {
        return [
            'planning' => '#6c757d',       // Secondary
            'in_progress' => '#17a2b8',    // Info
            'review' => '#ffc107',         // Yellow
            'completed' => '#28a745',      // Success
            'cancelled' => '#dc3545',      // Danger
            'on_hold' => '#fd7e14',        // Orange
            'archived' => '#6c757d',       // Secondary
            'pending_approval' => '#ffc107', // Yellow
            'testing' => '#007bff',        // Primary
            'deployed' => '#20c997',       // Teal
        ];
    }

    /**
     * Get icons for each status
     */
    protected static function icons(): array
    {
        return [
            'planning' => 'fa-drafting-compass',
            'in_progress' => 'fa-cogs',
            'review' => 'fa-eye',
            'completed' => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
            'on_hold' => 'fa-pause',
            'archived' => 'fa-archive',
            'pending_approval' => 'fa-hourglass-half',
            'testing' => 'fa-vial',
            'deployed' => 'fa-rocket',
        ];
    }

    /**
     * Check if project is active
     */
    public function isActive(): bool
    {
        return in_array($this, [
            self::PLANNING,
            self::IN_PROGRESS,
            self::REVIEW,
            self::TESTING,
        ]);
    }

    /**
     * Check if project is finished
     */
    public function isFinished(): bool
    {
        return in_array($this, [
            self::COMPLETED,
            self::CANCELLED,
            self::ARCHIVED,
            self::DEPLOYED,
        ]);
    }

    /**
     * Get progress percentage
     */
    public function progress(): int
    {
        return match ($this) {
            self::PLANNING => 10,
            self::IN_PROGRESS => 50,
            self::REVIEW => 70,
            self::TESTING => 85,
            self::DEPLOYED => 95,
            self::COMPLETED => 100,
            self::CANCELLED => 0,
            self::ON_HOLD => 30,
            self::ARCHIVED => 100,
            self::PENDING_APPROVAL => 20,
        };
    }
}