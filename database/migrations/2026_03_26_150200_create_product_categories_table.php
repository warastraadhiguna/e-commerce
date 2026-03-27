<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_type_id')
                ->constrained('product_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('product_categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->unique(['product_type_id', 'name']);
            $table->index(['product_type_id', 'parent_id', 'is_active', 'sort_order'], 'product_categories_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
