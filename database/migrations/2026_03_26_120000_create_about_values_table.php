<?php

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
        Schema::create('about_values', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 100);
            $table->text('description');
            $table->string('icon', 50);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        $timestamp = now();

        DB::table('about_values')->insert([
            [
                'title' => 'Fast Response',
                'description' => 'Respon cepat untuk kebutuhan pelanggan, pendaftaran mitra, dan tindak lanjut operasional.',
                'icon' => 'bolt',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'title' => 'Trustworthy',
                'description' => 'Proses bisnis dibangun dengan pendekatan yang aman, jelas, dan siap diaudit.',
                'icon' => 'shield',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'title' => 'Growth Focus',
                'description' => 'Kami menyiapkan fondasi digital yang mendukung pertumbuhan toko dan kemitraan jangka panjang.',
                'icon' => 'line-chart',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'title' => 'Partnership',
                'description' => 'Kami membuka ruang kolaborasi yang rapi dan mudah diakses melalui halaman pendaftaran mitra.',
                'icon' => 'users',
                'sort_order' => 4,
                'is_active' => true,
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
        Schema::dropIfExists('about_values');
    }
};
