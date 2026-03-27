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
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('product_type_id')
                ->constrained('product_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('product_category_id')
                ->constrained('product_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('measurement_unit_id')
                ->constrained('measurement_units')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('slug', 160)->unique();
            $table->string('barcode', 100)->nullable()->index();
            $table->string('name', 255);
            $table->string('short_name', 150)->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('compare_at_price', 15, 2)->nullable();
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->decimal('stock_quantity', 15, 3)->default(0);
            $table->decimal('min_order_quantity', 15, 3)->default(1);
            $table->decimal('unit_value', 12, 3)->default(1);
            $table->decimal('weight', 10, 3)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['brand_id', 'product_type_id', 'product_category_id'], 'products_catalog_idx');
            $table->index(['is_active', 'is_featured', 'is_new_arrival', 'is_bestseller'], 'products_flags_idx');
            $table->index(['published_at', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
