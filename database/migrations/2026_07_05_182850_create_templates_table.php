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
         Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('config')->nullable(); // Template configuration
            $table->json('default_data')->nullable(); // Default content/data
            $table->string('category')->nullable(); // business, portfolio, blog, ecommerce
            $table->string('version')->default('1.0.0');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('downloads')->default(0);
            $table->integer('rating')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
