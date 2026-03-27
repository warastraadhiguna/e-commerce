<?php

use App\Enums\MeasurementUnitType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('measurement_units', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('symbol', 20);
            $table->string('measurement_type', 30);
            $table->boolean('allow_decimal')->default(false);
            $table->unsignedTinyInteger('decimal_places')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['measurement_type', 'is_active', 'sort_order'], 'measurement_units_lookup_idx');
        });

        $timestamp = now();

        DB::table('measurement_units')->insert([
            [
                'name' => 'Pcs',
                'symbol' => 'pcs',
                'measurement_type' => MeasurementUnitType::Unit->value,
                'allow_decimal' => false,
                'decimal_places' => 0,
                'description' => 'Satuan per item atau per buah.',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Box',
                'symbol' => 'box',
                'measurement_type' => MeasurementUnitType::Packaging->value,
                'allow_decimal' => false,
                'decimal_places' => 0,
                'description' => 'Satuan per kotak atau kemasan box.',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'measurement_type' => MeasurementUnitType::Weight->value,
                'allow_decimal' => true,
                'decimal_places' => 3,
                'description' => 'Satuan berat dalam kilogram.',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Gram',
                'symbol' => 'g',
                'measurement_type' => MeasurementUnitType::Weight->value,
                'allow_decimal' => false,
                'decimal_places' => 0,
                'description' => 'Satuan berat dalam gram.',
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Liter',
                'symbol' => 'L',
                'measurement_type' => MeasurementUnitType::Volume->value,
                'allow_decimal' => true,
                'decimal_places' => 3,
                'description' => 'Satuan volume dalam liter.',
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Meter',
                'symbol' => 'm',
                'measurement_type' => MeasurementUnitType::Length->value,
                'allow_decimal' => true,
                'decimal_places' => 2,
                'description' => 'Satuan panjang dalam meter.',
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_units');
    }
};
