<?php

namespace App\Enums;

enum Environment: string
{
    use EnumTrait;

    case LOCAL = 'local';
    case DEVELOPMENT = 'development';
    case STAGING = 'staging';
    case TESTING = 'testing';
    case PRODUCTION = 'production';

    /**
     * Get descriptions for each environment
     */
    protected static function descriptions(): array
    {
        return [
            'local' => 'Local development environment',
            'development' => 'Development environment',
            'staging' => 'Staging environment',
            'testing' => 'Testing environment',
            'production' => 'Production environment',
        ];
    }

    /**
     * Get colors for each environment
     */
    protected static function colors(): array
    {
        return [
            'local' => '#28a745',    // Success
            'development' => '#17a2b8', // Info
            'staging' => '#ffc107',  // Yellow
            'testing' => '#fd7e14',  // Orange
            'production' => '#dc3545', // Danger
        ];
    }

    /**
     * Get icons for each environment
     */
    protected static function icons(): array
    {
        return [
            'local' => 'fa-laptop',
            'development' => 'fa-code',
            'staging' => 'fa-flask',
            'testing' => 'fa-vial',
            'production' => 'fa-server',
        ];
    }

    /**
     * Check if environment is production
     */
    public function isProduction(): bool
    {
        return $this === self::PRODUCTION;
    }

    /**
     * Check if environment is development
     */
    public function isDevelopment(): bool
    {
        return in_array($this, [
            self::LOCAL,
            self::DEVELOPMENT,
            self::TESTING,
        ]);
    }

    /**
     * Check if environment is staging
     */
    public function isStaging(): bool
    {
        return $this === self::STAGING;
    }

    /**
     * Get environment level
     */
    public function level(): int
    {
        return match ($this) {
            self::LOCAL => 1,
            self::DEVELOPMENT => 2,
            self::TESTING => 3,
            self::STAGING => 4,
            self::PRODUCTION => 5,
        };
    }
}