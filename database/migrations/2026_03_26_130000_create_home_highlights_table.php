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
        Schema::create('home_highlights', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 100);
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->string('fallback_asset')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        $timestamp = now();

        DB::table('home_highlights')->insert([
            [
                'title' => 'Free Delivery',
                'description' => 'Gratis pengiriman dan pengembalian. Lihat detail checkout untuk estimasi waktu kirim.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'title' => 'Safe Payment',
                'description' => 'Bayar dengan metode pembayaran populer yang aman dan tepercaya.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'title' => 'Shop with Confidence',
                'description' => 'Perlindungan pembeli membantu transaksi lebih nyaman dari pemesanan hingga barang diterima.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'title' => '24/7 Help Center',
                'description' => 'Punya pertanyaan? Tim kami siap membantu kapan saja.',
                'image_path' => null,
                'fallback_asset' => null,
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
        Schema::dropIfExists('home_highlights');
    }
};
