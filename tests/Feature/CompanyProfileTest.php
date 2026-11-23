<?php

namespace Tests\Feature;

use App\Models\AboutValue;
use App\Models\CompanyProfile;
use App\Models\HomeHighlight;
use App\Models\HomeMedia;
use App\Models\PartnerRegistration;
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
            'short_description' => 'Company short description',
            'long_description' => 'Company long description',
            'x_url' => 'https://x.com/andre',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSeeText('+62 812 0000 1111')
            ->assertSeeText('hello@andre-store.test')
            ->assertSeeText('Jakarta, Indonesia')
            ->assertSeeText('Company short description')
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

    public function test_contact_page_uses_company_profile_data(): void
    {
        CompanyProfile::query()->create([
            ...CompanyProfile::defaultAttributes(),
            'company_name' => 'Andre Store',
            'phone' => '+62 812 0000 1111',
            'email' => 'hello@andre-store.test',
            'address' => 'Jakarta, Indonesia',
            'short_description' => 'Company short description',
            'long_description' => 'Company long description',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=test-map',
            'instagram_url' => 'https://instagram.com/andre',
        ]);

        $this->get('/contact')
            ->assertOk()
            ->assertSeeText('Contact Us')
            ->assertSeeText('Pendaftaran Mitra')
            ->assertSeeText('Andre Store')
            ->assertSeeText('+62 812 0000 1111')
            ->assertSeeText('hello@andre-store.test')
            ->assertSeeText('Jakarta, Indonesia')
            ->assertSeeText('Company short description')
            ->assertSee('https://www.google.com/maps/embed?pb=test-map', false)
            ->assertSee('https://instagram.com/andre', false);
    }

    public function test_about_page_uses_company_profile_data(): void
    {
        CompanyProfile::query()->create([
            ...CompanyProfile::defaultAttributes(),
            'company_name' => 'Andre Store',
            'phone' => '+62 812 0000 1111',
            'email' => 'hello@andre-store.test',
            'short_description' => 'Company short description',
            'long_description' => 'Company long description',
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSeeText('About Us')
            ->assertSeeText('Andre Store')
            ->assertSeeText('+62 812 0000 1111')
            ->assertSeeText('hello@andre-store.test')
            ->assertSeeText('Company short description')
            ->assertSeeText('Company long description');
    }

    public function test_about_page_uses_database_driven_values(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        AboutValue::query()->create([
            'title' => 'Custom Value',
            'description' => 'Custom description from database.',
            'icon' => 'rocket',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSeeText('Custom Value')
            ->assertSeeText('Custom description from database.')
            ->assertSee('fa-rocket', false);
    }

    public function test_home_page_contact_link_points_to_contact_route(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->get('/')
            ->assertOk()
            ->assertSee(route('contact'), false);
    }

    public function test_home_page_about_link_points_to_about_route(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->get('/')
            ->assertOk()
            ->assertSee(route('about'), false);
    }

    public function test_home_page_uses_database_driven_highlights(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        HomeHighlight::query()->create([
            'title' => 'Custom Highlight',
            'description' => 'Custom highlight description from database.',
            'image_path' => null,
            'fallback_asset' => null,
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSeeText('Custom Highlight')
            ->assertSeeText('Custom highlight description from database.');
    }

    public function test_home_page_uses_storage_backed_home_media_images(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        HomeMedia::query()->create([
            'key' => 'custom_home_media',
            'label' => 'Custom Home Media',
            'section' => 'side_banner',
            'image_path' => 'home-media/custom-banner.jpg',
            'link_url' => '#',
            'sort_order' => 99,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/home-media/', false);
    }

    public function test_home_page_hides_highlight_image_when_image_is_empty(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        HomeHighlight::query()->create([
            'title' => 'No Image Highlight',
            'description' => 'Highlight without image.',
            'image_path' => null,
            'fallback_asset' => 'desain/images/shipping-icon/1.png',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('alt="No Image Highlight"', false);
    }

    public function test_partner_registration_submission_is_stored_as_pending(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->post('/contact/register', [
            'full_name' => '  Andre   Mantab  ',
            'whatsapp' => '+62 812 0000 1111',
            'message' => 'Saya ingin daftar sebagai mitra.',
            'website' => '',
        ])
            ->assertRedirect(route('contact').'#partner-registration')
            ->assertSessionHas('partner_registration_success');

        $this->assertDatabaseHas('partner_registrations', [
            'full_name' => 'Andre Mantab',
            'whatsapp' => '+62 812 0000 1111',
            'status' => 'pending',
        ]);
    }

    public function test_partner_registration_honeypot_is_rejected(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->from('/contact')
            ->post('/contact/register', [
                'full_name' => 'Andre Mantab',
                'whatsapp' => '+62 812 0000 1111',
                'message' => 'Spam',
                'website' => 'https://spam.test',
            ])
            ->assertRedirect(route('contact').'#partner-registration')
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('partner_registrations', 0);
    }

    public function test_management_company_profile_route_redirects_to_edit_page(): void
    {
        $admin = User::factory()->developer()->create();
        $companyProfile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->actingAs($admin)
            ->get('/management/company-profiles')
            ->assertRedirect("/management/company-profiles/{$companyProfile->id}/edit");
    }

    public function test_admin_can_view_partner_registration_list_with_status(): void
    {
        $admin = User::factory()->admin()->create();

        PartnerRegistration::query()->create([
            'full_name' => 'Andre Mantab',
            'whatsapp' => '+62 812 0000 1111',
            'message' => 'Saya ingin daftar.',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get('/management/partner-registrations')
            ->assertOk()
            ->assertSeeText('Andre Mantab')
            ->assertSeeText('Belum ditanggapi');
    }

    public function test_admin_can_view_about_values_list(): void
    {
        $admin = User::factory()->admin()->create();

        AboutValue::query()->create([
            'title' => 'Custom Value',
            'description' => 'Custom description from database.',
            'icon' => 'rocket',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/management/about-values')
            ->assertOk()
            ->assertSeeText('Custom Value')
            ->assertSeeText('rocket');
    }

    public function test_admin_can_view_home_highlights_list(): void
    {
        $admin = User::factory()->admin()->create();

        HomeHighlight::query()->create([
            'title' => 'Custom Highlight',
            'description' => 'Custom highlight description from database.',
            'image_path' => null,
            'fallback_asset' => null,
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/management/home-highlights')
            ->assertOk()
            ->assertSeeText('Custom Highlight');
    }

    public function test_admin_can_view_home_media_list(): void
    {
        $admin = User::factory()->admin()->create();

        HomeMedia::query()->create([
            'key' => 'custom_home_media',
            'label' => 'Custom Home Media',
            'section' => 'side_banner',
            'image_path' => 'home-media/custom-banner.jpg',
            'link_url' => '#',
            'sort_order' => 99,
        ]);

        $this->actingAs($admin)
            ->get('/management/home-media')
            ->assertOk()
            ->assertSeeText('Custom Home Media');
    }

    public function test_follow_us_is_hidden_when_no_social_media_is_filled(): void
    {
        CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->get('/')
            ->assertOk()
            ->assertDontSeeText('Follow Us');
    }
}
