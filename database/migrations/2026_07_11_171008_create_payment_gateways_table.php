<?php
// database/migrations/2024_01_01_000000_create_payment_gateways_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('gateway')->unique();
            $table->boolean('is_enabled')->default(false);
            $table->string('mode')->default('live');
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->json('currencies')->nullable();
            $table->json('settings')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};