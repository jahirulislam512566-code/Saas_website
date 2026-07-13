<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            
            // Basic Info
            $table->string('name')->nullable(); // Optional: custom name for the subscription
            $table->string('status')->default('pending'); // pending, active, trialing, past_due, canceled, paused, etc.
            $table->string('billing_cycle')->default('monthly');
            
            // Pricing
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('USD');
            
            // Dates
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('next_billing_at')->nullable();
            $table->timestamp('last_billing_at')->nullable();
            
            // Metadata
            $table->text('cancellation_reason')->nullable();
            $table->json('features')->nullable();
            $table->json('limits')->nullable();
            $table->json('metadata')->nullable();   // More flexible than 'settings'
            
            // Soft deletes & timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes for performance
            $table->index(['tenant_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['plan_id', 'status']);
            $table->index('expires_at');
            $table->index('next_billing_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};