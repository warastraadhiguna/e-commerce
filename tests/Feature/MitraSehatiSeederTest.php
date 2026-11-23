<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\CompanyProfile;
use App\Models\HomeMedia;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Support\CartManager;
use Database\Seeders\MitraSehatiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MitraSehatiSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');
        Http::preventStrayRequests();
    }

    public function test_seeder_installs_real_public_catalog_gallery_and_assets_without_network(): void
    {
        $this->seed(MitraSehatiSeeder::class);
        $this->assertDatabaseHas('company_profiles', ['company_name' => 'Mitra Sehati', 'theme_primary_color' => '#175298']);
        $this->assertDatabaseCount('products', 7);
        $this->assertSame(12, HomeMedia::query()->where('section', 'instagram_showcase')->count());
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('expenses', 0);
        foreach (Product::all() as $product) {
            $this->assertTrue($product->is_catalog_only);
            $this->assertSame('Harga belum tersedia', $product->displayPriceLabelFor(null));
            $this->assertNotNull($product->public_source_url);
            Storage::disk('public')->assertExists($product->thumbnail_path);
        }
        foreach (HomeMedia::whereIn('section', ['brand_hero', 'instagram_showcase'])->get() as $media) {
            Storage::disk('public')->assertExists($media->image_path);
        }
        $this->get('/')->assertOk()->assertSeeText('Mitra Sehati')->assertSeeText('Cerita & Kegiatan')
            ->assertSeeText('Teh Telang Malika')->assertDontSeeText('Chamcham Galaxy S9');
    }

    public function test_repeated_import_preserves_developer_edits_stock_prices_and_custom_content(): void
    {
        $this->seed(MitraSehatiSeeder::class);
        $profile = CompanyProfile::firstOrFail();
        $profile->update(['theme_primary_color' => '#112233', 'company_name' => 'Nama diperbarui']);
        $product = Product::where('sku', 'MS-IG-001')->firstOrFail();
        $product->update(['name' => 'Produk dikurasi', 'is_catalog_only' => false, 'price' => 45000, 'stock_quantity' => 18]);
        $gallery = HomeMedia::where('section', 'instagram_showcase')->firstOrFail();
        $gallery->update(['image_path' => 'custom/galeri.jpg']);
        $this->seed(MitraSehatiSeeder::class);
        $this->assertDatabaseCount('products', 7);
        $this->assertSame(12, HomeMedia::where('section', 'instagram_showcase')->count());
        $this->assertSame('#112233', $profile->fresh()->theme_primary_color);
        $this->assertSame('Nama diperbarui', $profile->fresh()->company_name);
        $this->assertSame('45000.00', $product->fresh()->price);
        $this->assertSame('18.000', $product->fresh()->stock_quantity);
        $this->assertFalse($product->fresh()->is_catalog_only);
        $this->assertSame('custom/galeri.jpg', $gallery->fresh()->image_path);
    }

    public function test_first_import_only_deactivates_original_dummy_products(): void
    {
        $brand = Brand::create(['name' => 'Existing', 'slug' => 'existing']);
        $type = ProductType::create(['name' => 'Existing', 'slug' => 'existing']);
        $category = ProductCategory::create(['name' => 'Existing', 'slug' => 'existing', 'product_type_id' => $type->id]);
        $unit = MeasurementUnit::create(['name' => 'Existing', 'symbol' => 'existing', 'measurement_type' => 'unit']);
        $attributes = ['brand_id' => $brand->id, 'product_type_id' => $type->id, 'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id, 'price' => 10000, 'stock_quantity' => 25, 'is_active' => true];
        $dummy = Product::create($attributes + ['sku' => 'DUMMY-PRD-001', 'slug' => 'dummy', 'name' => 'Produk demo']);
        $customer = Product::create($attributes + ['sku' => 'CUSTOMER-001', 'slug' => 'customer', 'name' => 'Produk pelanggan']);
        $this->seed(MitraSehatiSeeder::class);
        $this->assertFalse($dummy->fresh()->is_active);
        $this->assertTrue($customer->fresh()->is_active);
        $this->assertSame('25.000', $customer->fresh()->stock_quantity);
    }

    public function test_catalog_only_product_is_visible_but_cannot_be_purchased_or_checked_out(): void
    {
        $this->seed(MitraSehatiSeeder::class);
        $product = Product::where('sku', 'MS-IG-001')->firstOrFail();
        $this->get(route('products.show', $product->slug))->assertOk()->assertSeeText('Harga belum tersedia')
            ->assertSeeText('Lihat sumber Instagram')->assertDontSee('id="purchase-section"', false);
        $buyer = User::factory()->buyer()->create();
        $this->actingAs($buyer)->post(route('wishlist.store', $product))->assertRedirect();
        $this->get(route('wishlist.index'))->assertOk()->assertSeeText('Belum dikonfirmasi')->assertSeeText('Lihat katalog');
        $this->actingAs($buyer)->post(route('cart.store', $product), ['quantity' => 1])->assertUnprocessable();
        $address = ShippingAddress::create(['user_id' => $buyer->id, 'label' => 'Uji', 'recipient_name' => 'Uji', 'phone' => '08123456789', 'address_line' => 'Alamat uji']);
        $this->withSession([CartManager::SESSION_KEY => [$product->id => 1.0]])
            ->post(route('checkout.store'), ['shipping_address_id' => $address->id])->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_missing_storage_files_are_restored_on_rerun(): void
    {
        $this->seed(MitraSehatiSeeder::class);
        $path = Product::where('sku', 'MS-IG-001')->firstOrFail()->thumbnail_path;
        Storage::disk('public')->delete($path);
        $this->seed(MitraSehatiSeeder::class);
        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseCount('products', 7);
    }
}
