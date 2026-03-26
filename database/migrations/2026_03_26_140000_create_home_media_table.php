<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_media', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('label', 100);
            $table->string('section', 50);
            $table->string('image_path')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['section', 'sort_order']);
        });

        $copyToStorage = function (string $sourceRelativePath, string $targetRelativePath): ?string {
            $sourcePath = public_path($sourceRelativePath);

            if (! File::exists($sourcePath)) {
                return null;
            }

            Storage::disk('public')->put($targetRelativePath, File::get($sourcePath));

            return $targetRelativePath;
        };

        $timestamp = now();

        DB::table('home_media')->insert([
            [
                'key' => 'hero_slider_1',
                'label' => 'Banner Utama Depan 1',
                'section' => 'hero_slider',
                'image_path' => $copyToStorage('desain/images/slider/1.jpg', 'home-media/hero-slider-1.jpg'),
                'link_url' => null,
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'hero_slider_2',
                'label' => 'Banner Utama Depan 2',
                'section' => 'hero_slider',
                'image_path' => $copyToStorage('desain/images/slider/2.jpg', 'home-media/hero-slider-2.jpg'),
                'link_url' => null,
                'sort_order' => 2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'hero_slider_3',
                'label' => 'Banner Utama Depan 3',
                'section' => 'hero_slider',
                'image_path' => $copyToStorage('desain/images/slider/3.jpg', 'home-media/hero-slider-3.jpg'),
                'link_url' => null,
                'sort_order' => 3,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'side_banner_1',
                'label' => 'Banner Utama Atas',
                'section' => 'side_banner',
                'image_path' => $copyToStorage('desain/images/banner/1_1.jpg', 'home-media/side-banner-1.jpg'),
                'link_url' => '#',
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'side_banner_2',
                'label' => 'Banner Utama Bawah',
                'section' => 'side_banner',
                'image_path' => $copyToStorage('desain/images/banner/1_2.jpg', 'home-media/side-banner-2.jpg'),
                'link_url' => '#',
                'sort_order' => 2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'promo_banner_1',
                'label' => 'Banner Promo 1',
                'section' => 'promo_banner',
                'image_path' => $copyToStorage('desain/images/banner/1_3.jpg', 'home-media/promo-banner-1.jpg'),
                'link_url' => '#',
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'promo_banner_2',
                'label' => 'Banner Promo 2',
                'section' => 'promo_banner',
                'image_path' => $copyToStorage('desain/images/banner/1_4.jpg', 'home-media/promo-banner-2.jpg'),
                'link_url' => '#',
                'sort_order' => 2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'promo_banner_3',
                'label' => 'Banner Promo 3',
                'section' => 'promo_banner',
                'image_path' => $copyToStorage('desain/images/banner/1_5.jpg', 'home-media/promo-banner-3.jpg'),
                'link_url' => '#',
                'sort_order' => 3,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'key' => 'featured_banner_1',
                'label' => 'Banner Lebar Bawah',
                'section' => 'featured_banner',
                'image_path' => $copyToStorage('desain/images/bg-banner/1.jpg', 'home-media/featured-banner-1.jpg'),
                'link_url' => 'shop-left-sidebar.html',
                'sort_order' => 1,
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
        Schema::dropIfExists('home_media');
    }
};
