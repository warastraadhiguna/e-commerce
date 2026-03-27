<?php

namespace Tests\Feature;

use App\Enums\MeasurementUnitType;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\WishlistManager;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_added_to_wishlist_and_viewed(): void
    {
        $product = $this->createProduct();

        $this->post(route('wishlist.store', $product), [
            'redirect_to' => route('wishlist.index', [], false),
        ])->assertRedirect(route('wishlist.index'));

        $this->get(route('wishlist.index'))
            ->assertOk()
            ->assertSeeText($product->name);

        $this->assertSame([$product->id], session('wishlist_product_ids'));
    }

    public function test_product_is_not_duplicated_in_wishlist(): void
    {
        $product = $this->createProduct();

        $this->post(route('wishlist.store', $product), [
            'redirect_to' => route('wishlist.index', [], false),
        ]);

        $this->post(route('wishlist.store', $product), [
            'redirect_to' => route('wishlist.index', [], false),
        ]);

        $this->assertSame([$product->id], session('wishlist_product_ids'));
    }

    public function test_existing_product_is_not_removed_when_added_again(): void
    {
        $product = $this->createProduct();

        $this->withSession([
            'wishlist_product_ids' => [$product->id],
        ])->post(route('wishlist.store', $product), [
            'redirect_to' => route('wishlist.index', [], false),
        ])->assertRedirect(route('wishlist.index'))
            ->assertSessionHas('wishlist_feedback', [
                'icon' => 'info',
                'title' => 'Sudah ada di wishlist',
                'text' => 'Produk ini sudah ada di wishlist kamu.',
            ]);

        $this->assertSame([$product->id], session('wishlist_product_ids'));
    }

    public function test_product_can_be_removed_from_wishlist(): void
    {
        $product = $this->createProduct();

        $this->withSession([
            'wishlist_product_ids' => [$product->id],
        ])->delete(route('wishlist.destroy', $product), [
            'redirect_to' => route('wishlist.index', [], false),
        ])->assertRedirect(route('wishlist.index'));

        $this->get(route('wishlist.index'))
            ->assertOk()
            ->assertSeeText('Wishlist masih kosong');

        $this->assertSame([], session('wishlist_product_ids'));
    }

    public function test_wishlist_is_restored_from_cookie_when_session_is_empty(): void
    {
        $product = $this->createProduct();

        $response = $this->post(route('wishlist.store', $product), [
            'redirect_to' => route('wishlist.index', [], false),
        ]);

        $response->assertRedirect(route('wishlist.index'));
        $response->assertCookie(WishlistManager::COOKIE_KEY);

        $cookie = collect($response->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === WishlistManager::COOKIE_KEY);

        $this->flushSession();

        $response = $this->withCookie($cookie->getName(), $cookie->getValue())
            ->get(route('wishlist.index'));

        $response->assertOk()
            ->assertSeeText($product->name);
    }

    protected function createProduct(): Product
    {
        $brand = Brand::query()->create([
            'name' => 'Wishlist Brand',
            'slug' => 'wishlist-brand',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $productType = ProductType::query()->create([
            'name' => 'Wishlist Type',
            'slug' => 'wishlist-type',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $productType->id,
            'name' => 'Wishlist Category',
            'slug' => 'wishlist-category',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Wishlist',
            'symbol' => 'pcs',
            'measurement_type' => MeasurementUnitType::Unit,
            'allow_decimal' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return Product::query()->create([
            'brand_id' => $brand->id,
            'product_type_id' => $productType->id,
            'product_category_id' => $category->id,
            'measurement_unit_id' => $unit->id,
            'sku' => 'WISH-001',
            'slug' => 'wishlist-product',
            'name' => 'Produk Wishlist',
            'price' => 125000,
            'stock_quantity' => 12,
            'min_order_quantity' => 1,
            'unit_value' => 1,
            'is_active' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);
    }
}
