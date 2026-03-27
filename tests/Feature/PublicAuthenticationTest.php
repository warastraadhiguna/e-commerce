<?php

namespace Tests\Feature;

use App\Enums\PartnerDiscountStatus;
use App\Enums\MeasurementUnitType;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_login_page_is_accessible(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSeeText('Login');
    }

    public function test_buyer_can_login_from_public_page(): void
    {
        $buyer = User::factory()->buyer()->create([
            'email' => 'buyer@example.com',
            'phone' => '+628111111111',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'phone' => '0811 1111 111',
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($buyer);
    }

    public function test_inactive_user_cannot_login_from_public_page(): void
    {
        $inactiveBuyer = User::factory()->buyer()->create([
            'email' => 'inactive@example.com',
            'phone' => '+628122222222',
            'password' => 'password',
            'is_active' => false,
        ]);

        $this->post('/login', [
            'phone' => '0812-2222-222',
            'password' => 'password',
        ])->assertSessionHasErrors('phone');

        $this->assertGuest();
    }

    public function test_approved_mitra_sees_partner_discount_price(): void
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
            'name' => 'Pcs Mitra Test',
            'symbol' => 'pcs-mitra',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'MITRA-001',
            'slug' => 'produk-mitra',
            'name' => 'Produk Mitra',
            'price' => 100000,
            'partner_discount_price' => 85000,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $mitra = User::factory()->mitra()->create();

        $mitra->update([
            'business_name' => 'Mitra Test',
            'purchase_turnover_total' => 2000000,
            'minimum_turnover_for_discount' => 1000000,
            'partner_discount_status' => PartnerDiscountStatus::Approved,
            'partner_discount_approved_at' => now(),
        ]);

        $buyer = User::factory()->buyer()->create();

        $this->actingAs($mitra)
            ->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSeeText('Rp85.000,00')
            ->assertSeeText('Harga mitra aktif');

        auth()->logout();

        $this->actingAs($buyer)
            ->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSeeText('Rp100.000,00')
            ->assertDontSeeText('Harga mitra aktif');
    }

    public function test_buyer_can_add_to_cart_but_admin_cannot(): void
    {
        $brand = Brand::query()->create([
            'name' => 'Access Brand',
            'slug' => 'access-brand',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $productType = ProductType::query()->create([
            'name' => 'Access Type',
            'slug' => 'access-type',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Access Category',
            'slug' => 'access-category',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Akses',
            'symbol' => 'pcs-akses',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'BUY-001',
            'slug' => 'produk-akses-beli',
            'name' => 'Produk Akses Beli',
            'price' => 120000,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $buyer = User::factory()->buyer()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($buyer)
            ->post('/cart/'.$product->id, [
                'quantity' => 1,
                'redirect_to' => route('cart.index', [], false),
            ])
            ->assertRedirect(route('cart.index'));

        auth()->logout();

        $this->actingAs($admin)
            ->post('/cart/'.$product->id, [
                'quantity' => 1,
                'redirect_to' => route('home', [], false),
            ])
            ->assertForbidden();
    }
}
