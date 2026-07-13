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
         Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('version')->default('1.0.0');
            $table->json('config')->nullable(); // Theme configuration options
            $table->json('custom_css')->nullable();
            $table->json('custom_js')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->string('parent_theme')->nullable();
            $table->timestamps();
        });

         Schema::create('website_theme', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->foreignId('theme_id')->constrained()->onDelete('cascade');
            $table->json('settings')->nullable(); // Theme customizations
            $table->timestamps();
            $table->unique(['website_id', 'theme_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
        Schema::dropIfExists('website_theme');
    }
};
