<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            // Primary & Foreign Keys
            $table->id();
            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->onDelete('cascade');

            // Role Identification
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();

            // Security
            $table->string('guard_name')->default('web');

            // Status & Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_system')->default(false);
            $table->boolean('is_editable')->default(true);

            // UI Display
            $table->string('color')->nullable()->default('#6366f1');
            $table->string('icon')->nullable();
            $table->integer('priority')->default(0);

            // Metadata
            $table->json('metadata')->nullable();

            // Audit Trail
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Unique Constraints
            $table->unique(['tenant_id', 'name']);

            // Indexes for Performance
            $table->index(['tenant_id', 'is_default']);
            $table->index(['tenant_id', 'is_active']);
            $table->index(['tenant_id', 'is_system']);
            $table->index(['priority']);
            $table->index(['deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};