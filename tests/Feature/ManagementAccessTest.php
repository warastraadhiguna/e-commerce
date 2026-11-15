<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_management_login(): void
    {
        $this->get('/management')
            ->assertRedirect('/management/login');
    }

    public function test_admin_can_access_management_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/management')
            ->assertOk();
    }

    public function test_superadmin_can_access_management_dashboard(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get('/management')
            ->assertOk();
    }

    public function test_buyer_cannot_access_management_dashboard(): void
    {
        $buyer = User::factory()->buyer()->create();

        $this->actingAs($buyer)
            ->get('/management')
            ->assertForbidden();
    }

    public function test_admin_user_seeder_creates_management_admin_account(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => UserRole::Admin->value,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'superadmin@example.com',
            'role' => UserRole::SuperAdmin->value,
        ]);
    }

    public function test_admin_can_access_transaction_history_page(): void
    {
        $admin = User::factory()->admin()->create();
        $buyer = User::factory()->buyer()->create();

        Order::query()->create([
            'order_number' => 'ORD-MGMT-001',
            'user_id' => $buyer->id,
            'status' => OrderStatus::Pending,
            'customer_name' => $buyer->name,
            'customer_email' => $buyer->email,
            'customer_phone' => $buyer->phone,
            'customer_role' => $buyer->role->value,
            'uses_partner_pricing' => false,
            'shipping_recipient_name' => $buyer->name,
            'shipping_address_line' => 'Jl. Admin Test',
            'item_count' => 1,
            'total_quantity' => 1,
            'subtotal_amount' => 10000,
            'total_amount' => 10000,
            'checked_out_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/management/orders')
            ->assertOk()
            ->assertSeeText('Pesanan')
            ->assertSeeText('ORD-MGMT-001');
    }
}
