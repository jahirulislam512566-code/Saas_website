<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->nullable()->unique(); // 👈 MAKE NULLABLE
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->string('group')->nullable();
            $table->string('module')->nullable();
            $table->string('guard_name')->default('web');
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false);
            $table->boolean('is_editable')->default(true);
            
            $table->string('color')->nullable()->default('#8b5cf6');
            $table->string('icon')->nullable();
            $table->integer('priority')->default(0);
            
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'name']);
            $table->index(['tenant_id', 'group']);
            $table->index(['tenant_id', 'module']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};