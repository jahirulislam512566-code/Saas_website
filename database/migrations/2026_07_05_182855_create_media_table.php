<?php

// database/migrations/2024_01_01_000000_create_media_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('media_folders')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->nullableMorphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name')->default('default');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('url');
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->unsignedBigInteger('size');
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->json('metadata')->nullable();
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['tenant_id', 'folder_id']);
            $table->index('mime_type');
            $table->index('visibility');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('media_folders');
    }
};