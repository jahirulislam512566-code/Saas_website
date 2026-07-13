<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Newsletters Table
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            // Changed to composite unique to allow the same email on different websites
            $table->string('email');
            $table->unique(['email', 'website_id'], 'newsletter_email_website_unique');
            
            $table->string('name')->nullable();
            $table->json('preferences')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // 2. Newsletter Campaigns Table
        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('content');
            $table->string('status')->default('draft');
            $table->json('recipients')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_count')->default(0);
            $table->integer('open_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('bounce_count')->default(0);
            $table->integer('unsubscribe_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 3. Pivot Table with constrained index name to avoid "Identifier too long"
        Schema::create('newsletter_subscriber_campaign', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained('newsletters')->onDelete('cascade');
            $table->foreignId('newsletter_campaign_id')->constrained('newsletter_campaigns')->onDelete('cascade');
            $table->boolean('opened')->default(false);
            $table->boolean('clicked')->default(false);
            $table->boolean('unsubscribed')->default(false);
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
            
            // Explicitly named unique index to stay under 64 characters
            $table->unique(['newsletter_id', 'newsletter_campaign_id'], 'nsc_idx_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriber_campaign');
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletters');
    }
};