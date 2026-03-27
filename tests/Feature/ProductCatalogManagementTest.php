<?php

namespace Tests\Feature;

use App\Enums\MeasurementUnitType;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_brands_list(): void
    {
        $admin = User::factory()->admin()->create();

        Brand::query()->create([
            'name' => 'Sanai',
            'slug' => 'sanai',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get('/management/brands')
            ->assertOk()
            ->assertSeeText('Sanai');
    }

    public function test_admin_can_view_product_types_list(): void
    {
        $admin = User::factory()->admin()->create();

        ProductType::query()->create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get('/management/product-types')
            ->assertOk()
            ->assertSeeText('Laptop');
    }

    public function test_admin_can_view_product_categories_list(): void
    {
        $admin = User::factory()->admin()->create();

        $productType = ProductType::query()->create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Ultrabook',
            'slug' => 'ultrabook',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get('/management/product-categories')
            ->assertOk()
            ->assertSeeText('Ultrabook')
            ->assertSeeText('Laptop');
    }

    public function test_admin_can_view_measurement_units_list(): void
    {
        $admin = User::factory()->admin()->create();

        MeasurementUnit::query()->create([
            'name' => 'Pack',
            'symbol' => 'pack',
            'measurement_type' => MeasurementUnitType::Packaging,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $this->actingAs($admin)
            ->get('/management/measurement-units')
            ->assertOk()
            ->assertSeeText('Pack')
            ->assertSeeText('pack');
    }

    public function test_admin_can_view_products_list(): void
    {
        $admin = User::factory()->admin()->create();

        $brand = Brand::query()->create([
            'name' => 'Chamcham',
            'slug' => 'chamcham',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $productType = ProductType::query()->create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Ultrabook',
            'slug' => 'ultrabook',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Custom',
            'symbol' => 'pcs',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 99,
        ]);

        Product::query()->create([
            'brand_id' => $brand->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'SKU-LAP-001',
            'slug' => 'monitor-gaming-27-inch',
            'name' => 'Monitor Gaming 27 Inch',
            'price' => 1500000,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_bestseller' => false,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get('/management/products')
            ->assertOk()
            ->assertSeeText('Monitor Gaming 27 Inch')
            ->assertSeeText('Chamcham')
            ->assertSeeText('SKU-LAP-001');
    }

    public function test_home_page_uses_database_driven_products(): void
    {
        $brand = Brand::query()->create([
            'name' => 'Sanai',
            'slug' => 'sanai',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $productType = ProductType::query()->create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Computers',
            'slug' => 'computers',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Home',
            'symbol' => 'pcs',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 99,
        ]);

        Product::query()->create([
            'brand_id' => $brand->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'HOME-PRD-001',
            'slug' => 'speaker-portable-home',
            'name' => 'Speaker Portable Home',
            'excerpt' => 'Produk test untuk landing page.',
            'price' => 4680,
            'compare_at_price' => 5200,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_bestseller' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSeeText('Speaker Portable Home')
            ->assertSeeText('Sanai')
            ->assertSeeText('Laptop')
            ->assertSeeText('Product')
            ->assertSeeText('Computers')
            ->assertSeeText('All')
            ->assertDontSeeText('Blog')
            ->assertDontSeeText('Pages');
    }

    public function test_home_page_search_filters_products_by_keyword_and_filter(): void
    {
        $brandSanai = Brand::query()->create([
            'name' => 'Sanai',
            'slug' => 'sanai',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $brandChamcham = Brand::query()->create([
            'name' => 'Chamcham',
            'slug' => 'chamcham',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $productType = ProductType::query()->create([
            'name' => 'TV & Audio',
            'slug' => 'tv-audio',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Smart Audio',
            'slug' => 'smart-audio',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Search',
            'symbol' => 'pcs',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 99,
        ]);

        Product::query()->create([
            'brand_id' => $brandSanai->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'SEARCH-001',
            'slug' => 'speaker-pintar-sanai',
            'name' => 'Speaker Pintar Sanai',
            'price' => 4680,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_bestseller' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        Product::query()->create([
            'brand_id' => $brandChamcham->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'SEARCH-002',
            'slug' => 'kamera-chamcham',
            'name' => 'Kamera Chamcham',
            'price' => 7180,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_bestseller' => true,
            'published_at' => now(),
            'sort_order' => 2,
        ]);

        $this->get('/?q=Speaker&catalog=brand:'.$brandSanai->id)
            ->assertOk()
            ->assertSeeText('Hasil Pencarian')
            ->assertSeeText('Speaker Pintar Sanai')
            ->assertSeeText('Filter: [Brand] Sanai')
            ->assertSeeText('Keyword: Speaker');
    }

    public function test_product_detail_page_uses_database_product(): void
    {
        $brand = Brand::query()->create([
            'name' => 'Meito',
            'slug' => 'meito',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $productType = ProductType::query()->create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Prime Video',
            'slug' => 'prime-video',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Detail',
            'symbol' => 'pcs',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'DETAIL-001',
            'slug' => 'tablet-meito-plus',
            'name' => 'Tablet Meito Plus',
            'excerpt' => 'Tablet untuk presentasi dan operasional.',
            'description' => "Tablet Meito Plus cocok untuk kebutuhan presentasi.\nDidesain ringkas dan mudah dibawa.",
            'price' => 7180,
            'compare_at_price' => 7722,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_bestseller' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSeeText('Tablet Meito Plus')
            ->assertSeeText('Reference: DETAIL-001')
            ->assertSeeText('Meito')
            ->assertSeeText('Prime Video');

        $this->get('/')
            ->assertOk()
            ->assertSee(route('products.show', $product->slug));
    }
}
