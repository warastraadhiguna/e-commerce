<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\User;
use Database\Seeders\CompanyProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_profile_seeder_creates_default_profile(): void
    {
        $this->seed(CompanyProfileSeeder::class);

        $this->assertDatabaseHas('company_profiles', [
            'id' => 1,
            'email' => 'info@yourdomain.com',
        ]);
    }

    public function test_home_page_uses_company_profile_data(): void
    {
        CompanyProfile::query()->create([
            ...CompanyProfile::defaultAttributes(),
            'company_name' => 'Andre Store',
            'phone' => '+62 812 0000 1111',
            'email' => 'hello@andre-store.test',
            'address' => 'Jakarta, Indonesia',
            'description' => 'Company profile description',
            'x_url' => 'https://x.com/andre',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSeeText('+62 812 0000 1111')
            ->assertSeeText('hello@andre-store.test')
            ->assertSeeText('Jakarta, Indonesia')
            ->assertSeeText('Company profile description')
            ->assertSee('https://x.com/andre', false);
    }

    public function test_home_page_shows_company_name_when_logo_is_empty(): void
    {
        CompanyProfile::query()->create([
            ...CompanyProfile::defaultAttributes(),
            'company_name' => 'Andre Store',
            'logo_path' => null,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSeeText('Andre Store');
    }

    public function test_management_company_profile_route_redirects_to_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $companyProfile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->actingAs($admin)
            ->get('/management/company-profiles')
            ->assertRedirect("/management/company-profiles/{$companyProfile->id}/edit");
    }

    public function test_follow_us_is_hidden_when_no_social_media_is_filled(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->get('/')
            ->assertOk()
            ->assertDontSeeText('Follow Us');
    }
}
