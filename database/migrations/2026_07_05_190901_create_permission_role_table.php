<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_role', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Foreign Keys
            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            // Audit Trail - Track who assigned the permission
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
            $table->softDeletes();

            // Unique Constraints - Prevent duplicate assignments
            $table->unique(['permission_id', 'role_id', 'tenant_id'], 'permission_role_unique');
            
            // Indexes for Performance
            $table->index(['role_id', 'permission_id'], 'permission_role_role_permission_index');
            $table->index(['tenant_id'], 'permission_role_tenant_index');
            $table->index(['expires_at'], 'permission_role_expires_at_index');
            $table->index(['assigned_by'], 'permission_role_assigned_by_index');
            $table->index(['assigned_at'], 'permission_role_assigned_at_index');
            $table->index(['deleted_at'], 'permission_role_deleted_at_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};