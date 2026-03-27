<?php

namespace Database\Seeders;

use App\Enums\MeasurementUnitType;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $unit = MeasurementUnit::query()->firstOrCreate(
            ['symbol' => 'pcs'],
            [
                'name' => 'Pcs',
                'measurement_type' => MeasurementUnitType::Unit,
                'allow_decimal' => false,
                'decimal_places' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ],
        );

        $brands = collect([
            ['name' => 'Chamcham', 'slug' => 'chamcham', 'sort_order' => 1],
            ['name' => 'Sanai', 'slug' => 'sanai', 'sort_order' => 2],
            ['name' => 'Meito', 'slug' => 'meito', 'sort_order' => 3],
            ['name' => 'XailStation', 'slug' => 'xailstation', 'sort_order' => 4],
            ['name' => 'Graphic Corner', 'slug' => 'graphic-corner', 'sort_order' => 5],
            ['name' => 'Studio Design', 'slug' => 'studio-design', 'sort_order' => 6],
        ])->mapWithKeys(function (array $brand): array {
            $record = Brand::query()->updateOrCreate(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'description' => 'Brand dummy untuk landing page katalog produk.',
                    'is_active' => true,
                    'sort_order' => $brand['sort_order'],
                ],
            );

            return [$brand['slug'] => $record];
        });

        $types = collect([
            ['name' => 'Laptop', 'slug' => 'laptop', 'sort_order' => 1],
            ['name' => 'TV & Audio', 'slug' => 'tv-audio', 'sort_order' => 2],
        ])->mapWithKeys(function (array $type): array {
            $record = ProductType::query()->updateOrCreate(
                ['slug' => $type['slug']],
                [
                    'name' => $type['name'],
                    'description' => 'Tipe produk dummy untuk landing page.',
                    'is_active' => true,
                    'sort_order' => $type['sort_order'],
                ],
            );

            return [$type['slug'] => $record];
        });

        $categories = collect([
            ['type' => 'laptop', 'name' => 'Prime Video', 'slug' => 'prime-video', 'sort_order' => 1],
            ['type' => 'laptop', 'name' => 'Computers', 'slug' => 'computers', 'sort_order' => 2],
            ['type' => 'laptop', 'name' => 'Electronics', 'slug' => 'electronics', 'sort_order' => 3],
            ['type' => 'tv-audio', 'name' => 'Camera Accessories', 'slug' => 'camera-accessories', 'sort_order' => 1],
            ['type' => 'tv-audio', 'name' => 'Smart Audio', 'slug' => 'smart-audio', 'sort_order' => 2],
            ['type' => 'tv-audio', 'name' => 'Gaming Zone', 'slug' => 'gaming-zone', 'sort_order' => 3],
        ])->mapWithKeys(function (array $category) use ($types): array {
            $record = ProductCategory::query()->updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'product_type_id' => $types[$category['type']]->getKey(),
                    'parent_id' => null,
                    'name' => $category['name'],
                    'description' => 'Kategori dummy untuk landing page katalog produk.',
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                ],
            );

            return [$category['slug'] => $record];
        });

        $products = [
            [
                'sku' => 'DUMMY-PRD-001',
                'slug' => 'monitor-led-chamcham-24-inch',
                'name' => 'Monitor LED Chamcham 24 Inch',
                'short_name' => 'Monitor Chamcham 24"',
                'brand' => 'chamcham',
                'type' => 'laptop',
                'category' => 'computers',
                'image' => 'images/product/large-size/1.jpg',
                'price' => 4680,
                'compare_at_price' => null,
                'excerpt' => 'Monitor tipis untuk kebutuhan kerja dan kasir.',
                'description' => 'Monitor LED dummy untuk menghidupkan section landing page katalog.',
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'published_at' => now()->subDays(1),
            ],
            [
                'sku' => 'DUMMY-PRD-002',
                'slug' => 'kamera-360-studio-one',
                'name' => 'Kamera 360 Studio One',
                'short_name' => 'Kamera 360',
                'brand' => 'studio-design',
                'type' => 'tv-audio',
                'category' => 'camera-accessories',
                'image' => 'images/product/large-size/2.jpg',
                'price' => 7180,
                'compare_at_price' => 7722,
                'excerpt' => 'Kamera 360 ringkas untuk konten dan dokumentasi.',
                'description' => 'Kamera dummy untuk section new arrival dan trending.',
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'sku' => 'DUMMY-PRD-003',
                'slug' => 'gamepad-x1-wireless',
                'name' => 'Gamepad X1 Wireless',
                'short_name' => 'Gamepad X1',
                'brand' => 'xailstation',
                'type' => 'tv-audio',
                'category' => 'gaming-zone',
                'image' => 'images/product/large-size/3.jpg',
                'price' => 5299,
                'compare_at_price' => null,
                'excerpt' => 'Gamepad nirkabel untuk gaming santai maupun kompetitif.',
                'description' => 'Produk dummy untuk showcase brand XailStation.',
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'sku' => 'DUMMY-PRD-004',
                'slug' => 'monitor-pro-windows-27-inch',
                'name' => 'Monitor Pro Windows 27 Inch',
                'short_name' => 'Monitor Pro 27"',
                'brand' => 'graphic-corner',
                'type' => 'laptop',
                'category' => 'electronics',
                'image' => 'images/product/large-size/4.jpg',
                'price' => 7180,
                'compare_at_price' => 7722,
                'excerpt' => 'Monitor lebar untuk kebutuhan kantor dan desain.',
                'description' => 'Monitor dummy untuk featured product dan type section.',
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'published_at' => now()->subDays(4),
            ],
            [
                'sku' => 'DUMMY-PRD-005',
                'slug' => 'speaker-pintar-sanai-mini',
                'name' => 'Speaker Pintar Sanai Mini',
                'short_name' => 'Speaker Sanai Mini',
                'brand' => 'sanai',
                'type' => 'tv-audio',
                'category' => 'smart-audio',
                'image' => 'images/product/large-size/5.jpg',
                'price' => 4680,
                'compare_at_price' => null,
                'excerpt' => 'Speaker pintar minimalis untuk toko dan rumah.',
                'description' => 'Speaker dummy untuk tab trending brand Sanai.',
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'sku' => 'DUMMY-PRD-006',
                'slug' => 'drone-inspire-dual-controller',
                'name' => 'Drone Inspire Dual Controller',
                'short_name' => 'Drone Inspire',
                'brand' => 'meito',
                'type' => 'tv-audio',
                'category' => 'camera-accessories',
                'image' => 'images/product/large-size/6.jpg',
                'price' => 12900,
                'compare_at_price' => 14500,
                'excerpt' => 'Drone profesional untuk dokumentasi area luas.',
                'description' => 'Drone dummy untuk melengkapi variasi katalog home.',
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'published_at' => now()->subDays(6),
            ],
            [
                'sku' => 'DUMMY-PRD-007',
                'slug' => 'konsol-gamepad-dbox',
                'name' => 'Konsol Gamepad Dbox',
                'short_name' => 'Dbox Console',
                'brand' => 'xailstation',
                'type' => 'tv-audio',
                'category' => 'gaming-zone',
                'image' => 'images/product/large-size/7.jpg',
                'price' => 9999,
                'compare_at_price' => 10999,
                'excerpt' => 'Perangkat game modern untuk hiburan keluarga.',
                'description' => 'Konsol dummy untuk banner dan carousel produk.',
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'sku' => 'DUMMY-PRD-008',
                'slug' => 'headphone-air-bass',
                'name' => 'Headphone Air Bass',
                'short_name' => 'Air Bass',
                'brand' => 'sanai',
                'type' => 'tv-audio',
                'category' => 'smart-audio',
                'image' => 'images/product/large-size/8.jpg',
                'price' => 4680,
                'compare_at_price' => null,
                'excerpt' => 'Headphone nyaman untuk audio monitoring dan musik.',
                'description' => 'Headphone dummy untuk trending dan bestsellers.',
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'published_at' => now()->subDays(8),
            ],
            [
                'sku' => 'DUMMY-PRD-009',
                'slug' => 'tablet-meito-plus',
                'name' => 'Tablet Meito Plus',
                'short_name' => 'Tablet Meito',
                'brand' => 'meito',
                'type' => 'laptop',
                'category' => 'prime-video',
                'image' => 'images/product/large-size/9.jpg',
                'price' => 7180,
                'compare_at_price' => 7722,
                'excerpt' => 'Tablet serbaguna untuk operasional dan presentasi.',
                'description' => 'Tablet dummy untuk kategori Prime Video.',
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'published_at' => now()->subDays(9),
            ],
            [
                'sku' => 'DUMMY-PRD-010',
                'slug' => 'smartphone-chamcham-s9-plus',
                'name' => 'Smartphone Chamcham S9 Plus',
                'short_name' => 'Chamcham S9+',
                'brand' => 'chamcham',
                'type' => 'laptop',
                'category' => 'electronics',
                'image' => 'images/product/large-size/10.jpg',
                'price' => 9990,
                'compare_at_price' => 11200,
                'excerpt' => 'Smartphone dummy yang cocok untuk visual landing page.',
                'description' => 'Smartphone dummy untuk brand Chamcham.',
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'sku' => 'DUMMY-PRD-011',
                'slug' => 'smartwatch-meito-fit',
                'name' => 'Smartwatch Meito Fit',
                'short_name' => 'Meito Fit',
                'brand' => 'meito',
                'type' => 'tv-audio',
                'category' => 'smart-audio',
                'image' => 'images/product/large-size/11.jpg',
                'price' => 4680,
                'compare_at_price' => null,
                'excerpt' => 'Jam pintar untuk pemantauan aktivitas harian.',
                'description' => 'Smartwatch dummy untuk bestsellers.',
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'published_at' => now()->subDays(11),
            ],
            [
                'sku' => 'DUMMY-PRD-012',
                'slug' => 'speaker-portable-bassgo',
                'name' => 'Speaker Portable BassGo',
                'short_name' => 'BassGo',
                'brand' => 'sanai',
                'type' => 'tv-audio',
                'category' => 'smart-audio',
                'image' => 'images/product/large-size/12.jpg',
                'price' => 4680,
                'compare_at_price' => null,
                'excerpt' => 'Speaker portable untuk event kecil dan promo toko.',
                'description' => 'Speaker portable dummy untuk produk trending.',
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($products as $index => $product) {
            $thumbnailPath = $this->copyTemplateImage(
                $product['image'],
                sprintf('products/dummy/product-%02d.jpg', $index + 1),
            );

            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'brand_id' => $brands[$product['brand']]->getKey(),
                    'product_type_id' => $types[$product['type']]->getKey(),
                    'product_category_id' => $categories[$product['category']]->getKey(),
                    'measurement_unit_id' => $unit->getKey(),
                    'slug' => $product['slug'],
                    'barcode' => null,
                    'name' => $product['name'],
                    'short_name' => $product['short_name'],
                    'excerpt' => $product['excerpt'],
                    'description' => $product['description'],
                    'thumbnail_path' => $thumbnailPath,
                    'price' => $product['price'],
                    'compare_at_price' => $product['compare_at_price'],
                    'cost_price' => $product['price'] * 0.7,
                    'stock_quantity' => 25,
                    'min_order_quantity' => 1,
                    'unit_value' => 1,
                    'weight' => 1.250,
                    'length' => 25,
                    'width' => 18,
                    'height' => 10,
                    'is_active' => true,
                    'is_featured' => $product['is_featured'],
                    'is_new_arrival' => $product['is_new_arrival'],
                    'is_bestseller' => $product['is_bestseller'],
                    'published_at' => $product['published_at'],
                    'sort_order' => $index + 1,
                ],
            );
        }
    }

    private function copyTemplateImage(string $sourceRelativePath, string $targetRelativePath): ?string
    {
        $sourcePath = public_path('desain/'.$sourceRelativePath);

        if (! File::exists($sourcePath)) {
            return null;
        }

        $targetPath = storage_path('app/public/'.$targetRelativePath);

        File::ensureDirectoryExists(dirname($targetPath));
        File::copy($sourcePath, $targetPath);

        return $targetRelativePath;
    }
}
