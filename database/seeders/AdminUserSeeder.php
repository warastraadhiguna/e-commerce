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
            ['email' => env('MANAGEMENT_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('MANAGEMENT_ADMIN_NAME', 'Management Admin'),
                'password' => env('MANAGEMENT_ADMIN_PASSWORD', 'password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ],
        );
    }
}
