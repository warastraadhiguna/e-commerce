<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('MANAGEMENT_SUPERADMIN_EMAIL', 'superadmin@example.com')],
            [
                'name' => env('MANAGEMENT_SUPERADMIN_NAME', 'Management Superadmin'),
                'password' => env('MANAGEMENT_SUPERADMIN_PASSWORD', 'password'),
                'role' => UserRole::SuperAdmin,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => env('MANAGEMENT_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('MANAGEMENT_ADMIN_NAME', 'Management Admin'),
                'password' => env('MANAGEMENT_ADMIN_PASSWORD', 'password'),
                'role' => UserRole::Admin,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
