<?php

namespace Tests\Feature;

use App\Enums\MeasurementUnitType;
use App\Enums\PartnerDiscountStatus;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_checkout_cart_and_stock_is_reduced(): void
    {
        $buyer = User::factory()->buyer()->create();
        $address = $this->createShippingAddress($buyer);
        $product = $this->createProduct([
            'name' => 'Produk Checkout Buyer',
            'slug' => 'produk-checkout-buyer',
            'price' => 12000,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($buyer)->post(route('cart.store', $product), [
            'quantity' => 2,
            'redirect_to' => route('cart.index', [], false),
        ]);

        $response = $this->actingAs($buyer)
            ->post(route('checkout.store'), [
                'shipping_address_id' => $address->id,
                'user_notes' => 'Tolong kirim siang hari.',
            ]);

        $order = Order::query()->first();

        $response->assertRedirect(route('orders.show', $order));

        $this->assertNotNull($order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $buyer->id,
            'shipping_address_id' => $address->id,
            'item_count' => 1,
            'total_amount' => 24000.00,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'line_total' => 24000.00,
        ]);

        $product->refresh();
        $this->assertSame('8.000', (string) $product->stock_quantity);

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSeeText('Keranjang masih kosong');
    }

    public function test_checkout_rechecks_stock_before_creating_order(): void
    {
        $buyer = User::factory()->buyer()->create();
        $address = $this->createShippingAddress($buyer);
        $product = $this->createProduct([
            'name' => 'Produk Checkout Race',
            'slug' => 'produk-checkout-race',
            'price' => 25000,
            'stock_quantity' => 5,
        ]);

        $this->actingAs($buyer)->post(route('cart.store', $product), [
            'quantity' => 3,
            'redirect_to' => route('cart.index', [], false),
        ]);

        $product->update([
            'stock_quantity' => 2,
        ]);

        $this->from(route('checkout.show'))
            ->actingAs($buyer)
            ->post(route('checkout.store'), [
                'shipping_address_id' => $address->id,
            ])
            ->assertRedirect(route('checkout.show'))
            ->assertSessionHasErrors('checkout');

        $this->assertDatabaseCount('orders', 0);

        $product->refresh();
        $this->assertSame('2.000', (string) $product->stock_quantity);

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSeeText('Produk Checkout Race');
    }

    public function test_mitra_checkout_uses_partner_price_and_updates_turnover(): void
    {
        $mitra = User::factory()->mitra()->create([
            'purchase_turnover_total' => 0,
            'minimum_turnover_for_discount' => 0,
            'partner_discount_status' => PartnerDiscountStatus::Approved,
        ]);
        $address = $this->createShippingAddress($mitra);
        $product = $this->createProduct([
            'name' => 'Produk Harga Mitra',
            'slug' => 'produk-harga-mitra',
            'price' => 10000,
            'partner_discount_price' => 8000,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($mitra)->post(route('cart.store', $product), [
            'quantity' => 2,
            'redirect_to' => route('cart.index', [], false),
        ]);

        $this->actingAs($mitra)
            ->post(route('checkout.store'), [
                'shipping_address_id' => $address->id,
            ])
            ->assertRedirect();

        $order = Order::query()->firstOrFail();

        $this->assertSame('16000.00', (string) $order->total_amount);
        $this->assertTrue($order->uses_partner_pricing);

        $mitra->refresh();
        $this->assertSame('16000.00', (string) $mitra->purchase_turnover_total);
    }

    protected function createShippingAddress(User $user): ShippingAddress
    {
        return ShippingAddress::query()->create([
            'user_id' => $user->id,
            'label' => 'Gudang Utama',
            'recipient_name' => $user->name,
            'phone' => $user->phone,
            'address_line' => 'Jalan Test No. 1',
            'district' => 'Banyumanik',
            'city' => 'Semarang',
            'province' => 'Jawa Tengah',
            'postal_code' => '50264',
            'notes' => 'Samping minimarket',
            'is_default' => true,
            'sort_order' => 1,
        ]);
    }

    protected function createProduct(array $overrides = []): Product
    {
        $brand = Brand::query()->create([
            'name' => 'Brand Checkout',
            'slug' => 'brand-checkout',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $type = ProductType::query()->create([
            'name' => 'Tipe Checkout',
            'slug' => 'tipe-checkout',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $category = ProductCategory::query()->create([
            'product_type_id' => $type->id,
            'name' => 'Kategori Checkout',
            'slug' => 'kategori-checkout',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $unit = MeasurementUnit::query()->create([
            'name' => 'Pcs Checkout',
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
            'sku' => 'CHK-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT),
            'slug' => 'checkout-product-'.random_int(1000, 9999),
            'name' => 'Produk Checkout',
            'excerpt' => 'Produk test checkout.',
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
