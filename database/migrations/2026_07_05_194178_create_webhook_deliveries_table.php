<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained('webhooks')->onDelete('cascade');
            $table->string('event');
            $table->json('payload');
            $table->json('response')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('status')->default('pending');
            $table->integer('attempts')->default(0);
            $table->timestamp('next_attempt_at')->nullable();
            $table->timestamps();

            $table->index(['webhook_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
    }
};