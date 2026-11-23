<?php

namespace Database\Seeders;

use App\Enums\MeasurementUnitType;
use App\Models\Brand;
use App\Models\CompanyProfile;
use App\Models\HomeMedia;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MitraSehatiSeeder extends Seeder
{
    public function run(): void
    {
        $directory = database_path('seeders/data/mitra-sehati');
        $data = json_decode(file_get_contents($directory.'/catalog.json'), true, flags: JSON_THROW_ON_ERROR);
        $images = collect([$data['profile']['logo']])->merge(collect($data['products'])->pluck('image'))
            ->merge(collect($data['posts'])->pluck('image'))->unique();

        // All assets are bundled in Git. Deployment never needs access to Instagram.
        foreach ($images as $image) {
            if (! is_file($directory.'/'.$image)) {
                throw new RuntimeException('Gambar seeder tidak ditemukan: '.$image);
            }
        }
        foreach ($images as $image) {
            $destination = 'mitra-sehati/'.$image;
            if (! Storage::disk('public')->exists($destination)
                && ! Storage::disk('public')->put($destination, file_get_contents($directory.'/'.$image))) {
                throw new RuntimeException('Tidak dapat menyimpan gambar: '.$destination);
            }
        }

        DB::transaction(function () use ($data): void {
            $firstImport = ! HomeMedia::query()->where('key', 'mitra-sehati-brand-hero')->exists();
            $profile = CompanyProfile::query()->first()
                ?? CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

            if ($firstImport) {
                Storage::disk('local')->put('branding-backups/mitra-sehati-'.now()->format('Ymd-His-u').'.json', $profile->toJson());
                $attributes = [
                    'company_name' => $data['profile']['name'],
                    'logo_path' => 'mitra-sehati/'.$data['profile']['logo'],
                    'favicon_path' => 'mitra-sehati/'.$data['profile']['logo'],
                    'short_description' => $data['profile']['short_description'],
                    'long_description' => $data['profile']['long_description'],
                    'description' => $data['profile']['short_description'],
                    'instagram_url' => $data['source'],
                    'theme_primary_color' => $data['profile']['primary_color'],
                    'theme_secondary_color' => $data['profile']['secondary_color'],
                    'theme_background_color' => $data['profile']['background_color'],
                    'theme_font' => 'default', 'theme_pattern_path' => null,
                ];
                if ($profile->instagram_url !== $data['source']) {
                    $attributes += ['phone' => '', 'email' => '', 'address' => '', 'map_embed_url' => null,
                        'facebook_url' => null, 'x_url' => null, 'youtube_url' => null];
                }
                $profile->update($attributes);

                // Only disable the original presentation catalog; keep customer products and transactions.
                Product::query()->where('sku', 'like', 'DUMMY-PRD-%')->update(['is_active' => false]);
            }

            $unit = MeasurementUnit::query()->firstOrCreate(['symbol' => 'pcs'], [
                'name' => 'Pcs', 'measurement_type' => MeasurementUnitType::Unit,
                'allow_decimal' => false, 'decimal_places' => 0, 'is_active' => true, 'sort_order' => 1,
            ]);

            foreach ($data['products'] as $index => $item) {
                $brand = Brand::query()->firstOrCreate(['slug' => 'mitra-sehati-'.Str::slug($item['brand'])], [
                    'name' => $item['brand'], 'is_active' => true, 'sort_order' => $index + 1,
                ]);
                $type = ProductType::query()->firstOrCreate(['slug' => 'mitra-sehati-'.Str::slug($item['type'])], [
                    'name' => $item['type'], 'is_active' => true, 'sort_order' => $index + 1,
                ]);
                $category = ProductCategory::query()->firstOrCreate(['slug' => 'mitra-sehati-'.Str::slug($item['category'])], [
                    'name' => $item['category'], 'product_type_id' => $type->id,
                    'is_active' => true, 'sort_order' => $index + 1,
                ]);
                Product::query()->firstOrCreate(['sku' => $item['sku']], [
                    'slug' => 'mitra-sehati-'.Str::slug($item['name']), 'name' => $item['name'],
                    'brand_id' => $brand->id, 'product_type_id' => $type->id,
                    'product_category_id' => $category->id, 'measurement_unit_id' => $unit->id,
                    'excerpt' => $item['description'], 'description' => $item['description'],
                    'thumbnail_path' => 'mitra-sehati/'.$item['image'],
                    // Legacy numeric columns use zero internally; catalog-only UI never presents it as a price.
                    'price' => 0, 'stock_quantity' => 0, 'min_order_quantity' => 1, 'unit_value' => 1,
                    'is_active' => true, 'is_catalog_only' => true, 'source_url' => $item['source_url'],
                    'is_featured' => true, 'is_new_arrival' => true, 'is_bestseller' => false,
                    'published_at' => $data['collected_at'], 'sort_order' => $index + 1,
                ]);
            }

            HomeMedia::query()->firstOrCreate(['key' => 'mitra-sehati-brand-hero'], [
                'label' => 'Karya lokal, cerita Indonesia', 'section' => 'brand_hero',
                'image_path' => 'mitra-sehati/images/post-07.jpg', 'link_url' => $data['source'], 'sort_order' => 1,
            ]);
            foreach ($data['posts'] as $index => $post) {
                HomeMedia::query()->firstOrCreate(['key' => $post['key']], [
                    'label' => $post['title'], 'section' => 'instagram_showcase',
                    'image_path' => 'mitra-sehati/'.$post['image'],
                    'link_url' => $post['source_url'], 'sort_order' => $index + 1,
                ]);
            }
        });

        if (! file_exists(public_path('storage')) && ! is_link(public_path('storage'))) {
            Artisan::call('storage:link');
        }
        $this->command?->info('Mitra Sehati siap: 7 produk katalog informasi, 12 galeri, logo dan tema biru. Harga/stok belum dikonfirmasi; tidak dapat checkout.');
    }
}
