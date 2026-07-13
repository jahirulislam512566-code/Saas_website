<?php
// database/migrations/2024_01_01_000000_create_seo_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->nullableMorphs('model');
            
            // Meta tags
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            
            // Twitter Card
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('twitter_card')->default('summary_large_image');
            
            // Technical SEO
            $table->string('canonical_url')->nullable();
            $table->json('robots')->nullable();
            $table->json('json_ld')->nullable();
            
            // Content SEO
            $table->string('h1')->nullable();
            $table->json('h2')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->json('secondary_keywords')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            // $table->index(['model_type', 'model_id']);
            $table->index('is_active');
            $table->index('focus_keyword');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};