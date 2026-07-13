<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Pricing fields
            $table->decimal('price', 8, 2)->default(0); // Monthly Price
            $table->decimal('annual_price', 10, 2)->nullable(); // Added to match seeder
            $table->string('currency', 3)->default('USD');
            $table->string('billing_cycle')->default('monthly'); // Added to match seeder
            
            // Stripe/Payment
            $table->string('stripe_price_id_monthly')->nullable();
            $table->string('stripe_price_id_yearly')->nullable();
            
            // Content & Config
            $table->json('features')->nullable();
            $table->json('limits')->nullable();
            $table->integer('trial_days')->default(14);
            $table->string('support_level')->nullable(); // Added to match seeder
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false); // Added to match seeder
            $table->integer('sort_order')->default(0);
            
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};