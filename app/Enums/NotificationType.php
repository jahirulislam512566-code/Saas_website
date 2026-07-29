<?php

namespace App\Enums;

enum NotificationType: string
{
    use EnumTrait;

    case EMAIL = 'email';
    case SMS = 'sms';
    case PUSH = 'push';
    case IN_APP = 'in_app';
    case SLACK = 'slack';
    case TEAMS = 'teams';
    case WHATSAPP = 'whatsapp';

    /**
     * Get descriptions for each notification type
     */
    protected static function descriptions(): array
    {
        return [
            'email' => 'Email notification',
            'sms' => 'SMS text message',
            'push' => 'Push notification',
            'in_app' => 'In-app notification',
            'slack' => 'Slack message',
            'teams' => 'Microsoft Teams message',
            'whatsapp' => 'WhatsApp message',
        ];
    }

    /**
     * Get colors for each notification type
     */
    protected static function colors(): array
    {
        return [
            'email' => '#007bff',     // Primary
            'sms' => '#28a745',       // Success
            'push' => '#17a2b8',      // Info
            'in_app' => '#6c757d',    // Secondary
            'slack' => '#4a154b',     // Purple
            'teams' => '#464eb8',     // Blue
            'whatsapp' => '#25d366',  // Green
        ];
    }

    /**
     * Get icons for each notification type
     */
    protected static function icons(): array
    {
        return [
            'email' => 'fa-envelope',
            'sms' => 'fa-sms',
            'push' => 'fa-bell',
            'in_app' => 'fa-bell',
            'slack' => 'fa-slack',
            'teams' => 'fa-users',
            'whatsapp' => 'fa-whatsapp',
        ];
    }

    /**
     * Check if notification is instant
     */
    public function isInstant(): bool
    {
        return in_array($this, [
            self::PUSH,
            self::SMS,
            self::IN_APP,
        ]);
    }

    /**
     * Check if notification is required
     */
    public function isRequired(): bool
    {
        return in_array($this, [
            self::EMAIL,
            self::SMS,
            self::PUSH,
        ]);
    }
}