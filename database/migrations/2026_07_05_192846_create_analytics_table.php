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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('page_url')->nullable();
            $table->string('page_title')->nullable();
            $table->string('referrer')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->integer('session_duration')->nullable(); // in seconds
            $table->json('metadata')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
