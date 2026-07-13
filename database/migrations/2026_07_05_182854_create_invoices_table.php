<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('draft');
            $table->timestamp('issue_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('items')->nullable();
            $table->json('tax_details')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('invoice_number');
            $table->index('issue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};