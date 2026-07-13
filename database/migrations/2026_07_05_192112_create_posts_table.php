<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            
            // Relationships with explicit constraint names
            $table->foreignId('tenant_id')
                  ->constrained('tenants')
                  ->onDelete('cascade')
                  ->index();

            $table->foreignId('website_id')
                  ->constrained('websites')           // Make sure 'websites' table exists
                  ->onDelete('cascade')
                  ->index('posts_website_id_index');   // Explicit index name

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->index('posts_user_id_index');      // Explicit index name

            // Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();

            // Status & Publishing
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            // Analytics
            $table->unsignedBigInteger('views')->default(0);
            $table->integer('order')->default(0);

            // Metadata
            $table->json('settings')->nullable();
            $table->json('metadata')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Additional Indexes
            $table->index(['tenant_id', 'status']);
            $table->index(['website_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('published_at');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};