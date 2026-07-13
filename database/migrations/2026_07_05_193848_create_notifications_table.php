<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Multi-tenancy & Ownership with EXPLICIT constraint names
            $table->foreignId('tenant_id')
                  ->constrained('tenants')
                  ->onDelete('cascade')
                  ->index('notifications_tenant_id_index');

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->index('notifications_user_id_index');

            // Notification Content
            $table->string('type');
            $table->string('title')->nullable();
            $table->text('message')->nullable();

            // Polymorphic
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');

            // Data & Channels
            $table->json('data');
            $table->json('channels')->nullable();

            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->string('channel')->default('database');

            $table->timestamps();

            // Indexes
            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_index');
            $table->index(['tenant_id', 'is_read'], 'notifications_tenant_read_index');
            $table->index(['user_id', 'is_read'], 'notifications_user_read_index');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};