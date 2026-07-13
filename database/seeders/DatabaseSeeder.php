<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Database Seeding...');
        $this->command->info('');

        // Call all seeders in order
        $this->call([
            // First, create roles and permissions
            RoleSeeder::class,
            
            // Then create other data
            // PlanSeeder::class,
            // DepartmentSeeder::class,
            // ServiceCategorySeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            PortfolioCategorySeeder::class,
            PortfolioTagSeeder::class,
            // Add other seeders as needed
        ]);

        $this->command->info('');
        $this->command->info('✅ Database Seeding Complete!');
    }
}