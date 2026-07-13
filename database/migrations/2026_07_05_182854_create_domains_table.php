<?php
// database/migrations/2024_01_01_000000_create_domains_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('domain')->unique();
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->boolean('is_primary')->default(false);
            $table->boolean('ssl_enabled')->default(false);
            $table->timestamp('ssl_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['tenant_id', 'website_id']);
            $table->index('status');
            $table->index('is_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};