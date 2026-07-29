<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Services table
        if (!Schema::hasColumn('services', 'order')) {
            Schema::table('services', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('is_featured');
            });
        }

        // Portfolios table
        if (!Schema::hasColumn('portfolios', 'order')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('is_featured');
            });
        }

        // Team members table
        if (!Schema::hasColumn('team_members', 'order')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('is_active');
            });
        }

        // Testimonials table
        if (!Schema::hasColumn('testimonials', 'order')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('is_featured');
            });
        }

        // Blog posts table
        if (!Schema::hasColumn('blog_posts', 'order')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('is_featured');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['services', 'portfolios', 'team_members', 'testimonials', 'blog_posts'];
        
        foreach ($tables as $tableName) { // Changed: $table to $tableName
            if (Schema::hasColumn($tableName, 'order')) {
                Schema::table($tableName, function (Blueprint $table) { // Changed: removed 'use ($table)'
                    $table->dropColumn('order');
                });
            }
        }
    }
};