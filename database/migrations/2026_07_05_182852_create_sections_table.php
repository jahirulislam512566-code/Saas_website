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
         Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // hero, features, team, testimonials, contact, etc.
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('background_color')->nullable();
            $table->string('background_image')->nullable();
            $table->string('text_color')->nullable();
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
