<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CompanyProfile::query()->firstOrCreate(
            ['id' => 1],
            CompanyProfile::defaultAttributes(),
        );
    }
}
