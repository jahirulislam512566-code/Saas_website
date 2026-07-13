<?php
// database/seeders/PortfolioCategorySeeder.php

namespace Database\Seeders;

use App\Models\PortfolioCategory;
use Illuminate\Database\Seeder;

class PortfolioCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Applications',
                'slug' => 'web-applications',
                'description' => 'Custom web applications built with modern technologies.',
                'icon' => 'fa-globe',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mobile Apps',
                'slug' => 'mobile-apps',
                'description' => 'Native and cross-platform mobile applications.',
                'icon' => 'fa-mobile-alt',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise Solutions',
                'slug' => 'enterprise-solutions',
                'description' => 'Scalable enterprise-grade solutions for large organizations.',
                'icon' => 'fa-building',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'E-commerce',
                'slug' => 'ecommerce',
                'description' => 'Custom e-commerce platforms and online stores.',
                'icon' => 'fa-shopping-cart',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'SaaS Products',
                'slug' => 'saas-products',
                'description' => 'Software-as-a-Service products and platforms.',
                'icon' => 'fa-cloud',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'AI & Machine Learning',
                'slug' => 'ai-ml',
                'description' => 'AI-powered solutions and machine learning applications.',
                'icon' => 'fa-robot',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            PortfolioCategory::create($category);
        }
    }
}