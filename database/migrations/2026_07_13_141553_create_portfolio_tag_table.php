<?php
// database/migrations/2024_01_01_000003_create_portfolio_tag_table.php

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
        Schema::create('portfolio_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('portfolio_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['portfolio_id', 'portfolio_tag_id']);
            $table->index(['portfolio_id', 'portfolio_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_tag');
    }
};