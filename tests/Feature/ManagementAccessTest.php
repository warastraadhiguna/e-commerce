<?php

namespace Tests\Feature;

use App\Enums\UserRole;
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

    public function test_customer_cannot_access_management_dashboard(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
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
    }
}
