<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Business Pro',
                'slug' => 'business-pro',
                'category' => 'Business',
                'category_slug' => 'business',
                'description' => 'Modern business template with professional design and advanced features for corporate websites.',
                'image_url' => '/images/templates/business.jpg',
                'demo_url' => 'https://demo.business-pro.com',
                'price' => 49.00,
                'pages' => 12,
                'features' => ['Responsive Design', 'SEO Optimized', 'Blog Ready', 'Contact Form', 'Google Maps'],
                'colors' => ['#4F46E5', '#7C3AED', '#EC4899'],
                'rating' => 4.8,
                'reviews' => 156,
                'popular' => true,
                'is_free' => false,
                'default_settings' => [
                    'layout' => 'wide',
                    'sidebar' => 'right',
                    'header_style' => 'fixed',
                ],
                'sections' => [
                    ['type' => 'hero', 'title' => 'Hero Section'],
                    ['type' => 'features', 'title' => 'Features'],
                    ['type' => 'testimonials', 'title' => 'Testimonials'],
                ],
                'pages_data' => [
                    ['title' => 'Home', 'is_home' => true],
                    ['title' => 'About', 'is_home' => false],
                    ['title' => 'Services', 'is_home' => false],
                    ['title' => 'Contact', 'is_home' => false],
                ],
            ],
            [
                'name' => 'Agency Modern',
                'slug' => 'agency-modern',
                'category' => 'Agency',
                'category_slug' => 'agency',
                'description' => 'Modern agency template with project showcase and team management features.',
                'image_url' => '/images/templates/agency.jpg',
                'demo_url' => 'https://demo.agency-modern.com',
                'price' => 0,
                'pages' => 14,
                'features' => ['Responsive Design', 'Project Showcase', 'Team Management', 'Client Portal'],
                'colors' => ['#7C3AED', '#4F46E5', '#EC4899'],
                'rating' => 4.9,
                'reviews' => 203,
                'popular' => true,
                'is_free' => true,
                'default_settings' => [
                    'layout' => 'boxed',
                    'sidebar' => 'left',
                    'header_style' => 'sticky',
                ],
                'sections' => [
                    ['type' => 'hero', 'title' => 'Hero Section'],
                    ['type' => 'portfolio', 'title' => 'Portfolio'],
                    ['type' => 'team', 'title' => 'Team'],
                ],
                'pages_data' => [
                    ['title' => 'Home', 'is_home' => true],
                    ['title' => 'Portfolio', 'is_home' => false],
                    ['title' => 'Team', 'is_home' => false],
                    ['title' => 'Contact', 'is_home' => false],
                ],
            ],
            [
                'name' => 'Restaurant Deluxe',
                'slug' => 'restaurant-deluxe',
                'category' => 'Restaurant',
                'category_slug' => 'restaurant',
                'description' => 'Beautiful restaurant template with menu showcase and reservation system.',
                'image_url' => '/images/templates/restaurant.jpg',
                'demo_url' => 'https://demo.restaurant-deluxe.com',
                'price' => 59.00,
                'pages' => 8,
                'features' => ['Menu Builder', 'Reservation System', 'Gallery', 'Google Maps'],
                'colors' => ['#DC2626', '#F59E0B', '#92400E'],
                'rating' => 4.7,
                'reviews' => 89,
                'popular' => true,
                'is_free' => false,
                'default_settings' => [
                    'layout' => 'wide',
                    'sidebar' => 'none',
                    'header_style' => 'transparent',
                ],
                'sections' => [
                    ['type' => 'hero', 'title' => 'Hero Section'],
                    ['type' => 'menu', 'title' => 'Menu'],
                    ['type' => 'gallery', 'title' => 'Gallery'],
                ],
                'pages_data' => [
                    ['title' => 'Home', 'is_home' => true],
                    ['title' => 'Menu', 'is_home' => false],
                    ['title' => 'Reservations', 'is_home' => false],
                    ['title' => 'Contact', 'is_home' => false],
                ],
            ],
            // Add more templates for other categories...
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}