<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method');
            $table->string('payment_gateway')->nullable();
            $table->string('status')->default('pending');
            $table->string('transaction_id')->unique();
            $table->string('gateway_transaction_id')->nullable();
            $table->text('description')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('refund_reason')->nullable();
            $table->boolean('is_refunded')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('transaction_id');
            $table->index('payment_date');
            $table->index('gateway_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};