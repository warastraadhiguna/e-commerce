<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_access_account_page(): void
    {
        $buyer = User::factory()->buyer()->create();

        $this->actingAs($buyer)
            ->get(route('account.show'))
            ->assertOk()
            ->assertSeeText('Profil Akun')
            ->assertSeeText('Alamat Kirim');
    }

    public function test_admin_cannot_access_account_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('account.show'))
            ->assertForbidden();
    }

    public function test_buyer_can_update_basic_profile_fields(): void
    {
        $buyer = User::factory()->buyer()->create([
            'business_name' => null,
            'contact_person' => null,
            'address' => null,
        ]);

        $this->actingAs($buyer)
            ->put(route('account.profile.update'), [
                'name' => 'Buyer Baru',
                'email' => 'buyer-baru@example.com',
                'phone' => '081234567891',
                'business_name' => 'Toko Baru',
                'contact_person' => 'Andi',
                'address' => 'Jl. Mawar No. 10',
            ])
            ->assertRedirect(route('account.show', ['tab' => 'profile']));

        $buyer->refresh();

        $this->assertSame('Buyer Baru', $buyer->name);
        $this->assertSame('buyer-baru@example.com', $buyer->email);
        $this->assertSame('+6281234567891', $buyer->phone);
        $this->assertSame('Toko Baru', $buyer->business_name);
        $this->assertSame('Andi', $buyer->contact_person);
        $this->assertSame('Jl. Mawar No. 10', $buyer->address);
    }

    public function test_buyer_can_create_multiple_shipping_addresses(): void
    {
        $buyer = User::factory()->buyer()->create();

        $this->actingAs($buyer)
            ->post(route('account.shipping-addresses.store'), [
                'label' => 'Rumah',
                'recipient_name' => 'Budi',
                'phone' => '081234567890',
                'address_line' => 'Jl. Kenanga No. 1',
                'city' => 'Semarang',
                'is_default' => '1',
            ])
            ->assertRedirect(route('account.show', ['tab' => 'shipping']));

        $this->actingAs($buyer)
            ->post(route('account.shipping-addresses.store'), [
                'label' => 'Gudang',
                'recipient_name' => 'Budi',
                'phone' => '081234567891',
                'address_line' => 'Jl. Melati No. 2',
                'city' => 'Semarang',
            ])
            ->assertRedirect(route('account.show', ['tab' => 'shipping']));

        $this->assertDatabaseCount('shipping_addresses', 2);
        $this->assertDatabaseHas('shipping_addresses', [
            'user_id' => $buyer->id,
            'label' => 'Rumah',
            'is_default' => true,
        ]);
        $this->assertDatabaseHas('shipping_addresses', [
            'user_id' => $buyer->id,
            'label' => 'Gudang',
            'is_default' => false,
        ]);
    }

    public function test_default_shipping_address_switches_when_updated(): void
    {
        $buyer = User::factory()->buyer()->create();

        $first = ShippingAddress::query()->create([
            'user_id' => $buyer->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'phone' => '081234567890',
            'address_line' => 'Jl. Kenanga No. 1',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $second = ShippingAddress::query()->create([
            'user_id' => $buyer->id,
            'label' => 'Gudang',
            'recipient_name' => 'Budi',
            'phone' => '081234567891',
            'address_line' => 'Jl. Melati No. 2',
            'is_default' => false,
            'sort_order' => 2,
        ]);

        $this->actingAs($buyer)
            ->put(route('account.shipping-addresses.update', $second), [
                'label' => 'Gudang',
                'recipient_name' => 'Budi',
                'phone' => '081234567891',
                'address_line' => 'Jl. Melati No. 2',
                'is_default' => '1',
            ])
            ->assertRedirect(route('account.show', ['tab' => 'shipping']));

        $this->assertDatabaseHas('shipping_addresses', [
            'id' => $first->id,
            'is_default' => false,
        ]);
        $this->assertDatabaseHas('shipping_addresses', [
            'id' => $second->id,
            'is_default' => true,
        ]);
    }

    public function test_buyer_can_change_password_from_account_page(): void
    {
        $buyer = User::factory()->buyer()->create([
            'password' => 'password',
        ]);

        $this->actingAs($buyer)
            ->put(route('account.password.update'), [
                'current_password' => 'password',
                'password' => 'passwordBaru123',
                'password_confirmation' => 'passwordBaru123',
                'account_tab' => 'password',
            ])
            ->assertRedirect(route('account.show', ['tab' => 'password']));

        $buyer->refresh();

        $this->assertTrue(Hash::check('passwordBaru123', $buyer->password));
    }

    public function test_buyer_can_see_order_history_in_account_page(): void
    {
        $buyer = User::factory()->buyer()->create();

        Order::query()->create([
            'order_number' => 'ORD-TEST-001',
            'user_id' => $buyer->id,
            'status' => OrderStatus::Pending,
            'customer_name' => $buyer->name,
            'customer_email' => $buyer->email,
            'customer_phone' => $buyer->phone,
            'customer_role' => $buyer->role->value,
            'uses_partner_pricing' => false,
            'shipping_recipient_name' => $buyer->name,
            'shipping_address_line' => 'Jl. Test',
            'item_count' => 1,
            'total_quantity' => 2,
            'subtotal_amount' => 100000,
            'total_amount' => 100000,
            'checked_out_at' => now(),
        ]);

        $this->actingAs($buyer)
            ->get(route('account.show', ['tab' => 'orders']))
            ->assertOk()
            ->assertSeeText('Riwayat Order')
            ->assertSeeText('ORD-TEST-001')
            ->assertSeeText('Pending');
    }
}
