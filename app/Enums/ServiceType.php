<?php

namespace App\Enums;

enum ServiceType: string
{
    use EnumTrait;

    case WEBSITE_DESIGN = 'website_design';
    case WEB_DEVELOPMENT = 'web_development';
    case SEO = 'seo';
    case MAINTENANCE = 'maintenance';
    case HOSTING = 'hosting';
    case MOBILE_APP = 'mobile_app';
    case GRAPHIC_DESIGN = 'graphic_design';
    case CONTENT_WRITING = 'content_writing';
    case SOCIAL_MEDIA = 'social_media';
    case DIGITAL_MARKETING = 'digital_marketing';
    case CONSULTING = 'consulting';
    case TRAINING = 'training';

    /**
     * Get descriptions for each service type
     */
    protected static function descriptions(): array
    {
        return [
            'website_design' => 'Professional website design services',
            'web_development' => 'Full-stack web development services',
            'seo' => 'Search engine optimization services',
            'maintenance' => 'Website maintenance services',
            'hosting' => 'Web hosting services',
            'mobile_app' => 'Mobile application development',
            'graphic_design' => 'Graphic design services',
            'content_writing' => 'Content writing and copywriting',
            'social_media' => 'Social media management',
            'digital_marketing' => 'Digital marketing services',
            'consulting' => 'Business consulting services',
            'training' => 'Training and workshop services',
        ];
    }

    /**
     * Get colors for each service type
     */
    protected static function colors(): array
    {
        return [
            'website_design' => '#007bff',   // Primary
            'web_development' => '#28a745',  // Success
            'seo' => '#ffc107',              // Yellow
            'maintenance' => '#17a2b8',      // Info
            'hosting' => '#6f42c1',          // Purple
            'mobile_app' => '#fd7e14',       // Orange
            'graphic_design' => '#e83e8c',   // Pink
            'content_writing' => '#20c997',  // Teal
            'social_media' => '#17a2b8',     // Info
            'digital_marketing' => '#dc3545', // Danger
            'consulting' => '#6c757d',       // Secondary
            'training' => '#28a745',         // Success
        ];
    }

    /**
     * Get icons for each service type
     */
    protected static function icons(): array
    {
        return [
            'website_design' => 'fa-paint-brush',
            'web_development' => 'fa-code',
            'seo' => 'fa-chart-line',
            'maintenance' => 'fa-tools',
            'hosting' => 'fa-server',
            'mobile_app' => 'fa-mobile-alt',
            'graphic_design' => 'fa-palette',
            'content_writing' => 'fa-pen-fancy',
            'social_media' => 'fa-share-alt',
            'digital_marketing' => 'fa-bullhorn',
            'consulting' => 'fa-chalkboard-teacher',
            'training' => 'fa-chalkboard',
        ];
    }

    /**
     * Get category for service type
     */
    public function category(): string
    {
        return match ($this) {
            self::WEBSITE_DESIGN, self::WEB_DEVELOPMENT, self::MOBILE_APP => 'Development',
            self::SEO, self::DIGITAL_MARKETING, self::SOCIAL_MEDIA => 'Marketing',
            self::MAINTENANCE, self::HOSTING => 'Infrastructure',
            self::GRAPHIC_DESIGN, self::CONTENT_WRITING => 'Creative',
            self::CONSULTING, self::TRAINING => 'Professional Services',
        };
    }
}