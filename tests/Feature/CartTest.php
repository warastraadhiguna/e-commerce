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

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_add_product_to_cart_and_see_it_in_cart_page(): void
    {
        $buyer = User::factory()->buyer()->create();
        $product = $this->createProduct([
            'name' => 'Produk Cart Test',
            'slug' => 'produk-cart-test',
            'price' => 12000,
        ]);

        $this->actingAs($buyer)
            ->post(route('cart.store', $product), [
                'quantity' => 2,
                'redirect_to' => route('cart.index', [], false),
            ])
            ->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSeeText('Produk Cart Test')
            ->assertSeeText('1 pcs')
            ->assertSeeText('Rp24.000,00');
    }

    public function test_buyer_can_update_cart_quantity_from_cart_page(): void
    {
        $buyer = User::factory()->buyer()->create();
        $product = $this->createProduct([
            'name' => 'Produk Update Cart',
            'slug' => 'produk-update-cart',
            'price' => 18000,
        ]);

        $this->actingAs($buyer)
            ->post(route('cart.store', $product), [
                'quantity' => 1,
                'redirect_to' => route('cart.index', [], false),
            ]);

        $this->actingAs($buyer)
            ->put(route('cart.update', $product), [
                'quantity' => 3,
                'redirect_to' => route('cart.index', [], false),
            ])
            ->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSeeText('Rp54.000,00')
            ->assertSee('value="3"', false);
    }

    public function test_home_page_shows_dynamic_cart_badge_and_minicart_after_add_to_cart(): void
    {
        $buyer = User::factory()->buyer()->create();
        $product = $this->createProduct([
            'name' => 'Produk Header Cart',
            'slug' => 'produk-header-cart',
            'price' => 15000,
            'is_new_arrival' => true,
            'is_featured' => true,
            'is_bestseller' => true,
        ]);

        $this->actingAs($buyer)
            ->post(route('cart.store', $product), [
                'quantity' => 3,
                'redirect_to' => route('home', [], false),
            ])
            ->assertRedirect(route('home'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Produk Header Cart')
            ->assertSeeText('Rp45.000,00')
            ->assertSee('class="cart-item-count">3</span>', false);
    }

    public function test_product_is_removed_from_wishlist_after_it_is_added_to_cart(): void
    {
        $buyer = User::factory()->buyer()->create();
        $product = $this->createProduct([
            'name' => 'Produk Pindah Wishlist',
            'slug' => 'produk-pindah-wishlist',
        ]);

        $this->withSession([
            'wishlist_product_ids' => [$product->id],
        ])->actingAs($buyer)
            ->post(route('cart.store', $product), [
                'quantity' => 1,
                'redirect_to' => route('cart.index', [], false),
            ])
            ->assertRedirect(route('cart.index'));

        $this->assertSame([], session('wishlist_product_ids'));

        $this->get(route('wishlist.index'))
            ->assertOk()
            ->assertSeeText('Wishlist masih kosong')
            ->assertSee('class="cart-item-count wishlist-item-count">0</span>', false);
    }

    public function test_admin_cannot_add_product_to_cart(): void
    {
        $admin = User::factory()->admin()->create();
        $product = $this->createProduct([
            'name' => 'Produk Admin Cart',
            'slug' => 'produk-admin-cart',
        ]);

        $this->actingAs($admin)
            ->post(route('cart.store', $product), [
                'quantity' => 1,
                'redirect_to' => route('home', [], false),
            ])
            ->assertForbidden();
    }

    protected function createProduct(array $overrides = []): Product
    {
        $brand = Brand::query()->create([
            'name' => 'Brand Cart',
            'slug' => 'brand-cart',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $type = ProductType::query()->create([
            'name' => 'Tipe Cart',
            'slug' => 'tipe-cart',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $type->id,
            'name' => 'Kategori Cart',
            'slug' => 'kategori-cart',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Cart',
            'symbol' => 'pcs',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return Product::query()->create(array_merge([
            'brand_id' => $brand->id,
            'product_type_id' => $type->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'CART-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT),
            'slug' => 'cart-product-'.random_int(1000, 9999),
            'name' => 'Produk Cart',
            'excerpt' => 'Produk test cart.',
            'price' => 10000,
            'stock_quantity' => 10,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ], $overrides));
    }
}
