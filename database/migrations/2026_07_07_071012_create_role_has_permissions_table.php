<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists before creating
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
                $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['permission_id', 'role_id', 'tenant_id']);
            });
        }

        // Also create user_has_roles if needed
        if (!Schema::hasTable('user_has_roles')) {
            Schema::create('user_has_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['role_id', 'user_id', 'tenant_id']);
                $table->index(['user_id', 'tenant_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('user_has_roles');
    }
};