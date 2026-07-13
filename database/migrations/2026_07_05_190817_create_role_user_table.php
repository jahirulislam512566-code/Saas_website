<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Foreign Keys
            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            // Audit Trail - Track who assigned the role
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            
            // Assignment Details
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            
            // Additional Data
            $table->json('metadata')->nullable();
            
            // Timestamps
            $table->timestamps();

            // Unique Constraints - Prevent duplicate assignments
            $table->unique(['role_id', 'user_id', 'tenant_id'], 'role_user_unique');
            
            // Indexes for Performance
            $table->index(['user_id', 'role_id'], 'role_user_user_role_index');
            $table->index(['tenant_id'], 'role_user_tenant_index');
            $table->index(['expires_at'], 'role_user_expires_at_index');
            $table->index(['assigned_by'], 'role_user_assigned_by_index');
            $table->index(['assigned_at'], 'role_user_assigned_at_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};