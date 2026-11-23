<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\CompanyProfiles\Pages\EditCompanyProfile;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DeveloperSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_active_developer_can_open_brand_settings(): void
    {
        $profile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());
        $this->actingAs(User::factory()->developer()->create())->get('/management')->assertOk();
        $this->get('/management/company-profiles')->assertRedirect('/management/company-profiles/'.$profile->id.'/edit');
        $this->get('/management/company-profiles/'.$profile->id.'/edit')->assertOk()->assertSeeText('Tema toko');
        $this->actingAs(User::factory()->developer()->create(['is_active' => false]))
            ->get('/management/company-profiles/'.$profile->id.'/edit')->assertForbidden();
    }

    public static function nonDeveloperRoles(): array
    {
        return array_map(fn (UserRole $role): array => [$role], [UserRole::SuperAdmin, UserRole::Admin, UserRole::Buyer, UserRole::Mitra]);
    }

    #[DataProvider('nonDeveloperRoles')]
    public function test_other_roles_cannot_open_or_edit_brand_settings(UserRole $role): void
    {
        $profile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());
        $user = User::factory()->create(['role' => $role]);
        $this->actingAs($user)->get('/management/company-profiles')->assertForbidden();
        $this->get('/management/company-profiles/'.$profile->id.'/edit')->assertForbidden();
        $this->assertFalse(Gate::forUser($user)->allows('update', $profile));
    }

    public function test_settings_navigation_is_hidden_for_admin(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/management')
            ->assertOk()->assertDontSeeText('Identitas & Tema');
    }

    public function test_developer_can_save_colors_font_logo_favicon_and_pattern(): void
    {
        Storage::fake('public');
        $profile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());
        $this->actingAs(User::factory()->developer()->create());
        Livewire::test(EditCompanyProfile::class, ['record' => $profile->id])->fillForm([
            'theme_primary_color' => '#156538', 'theme_secondary_color' => '#a84a17',
            'theme_background_color' => '#fff9ed', 'theme_font' => 'serif',
            'logo_path' => UploadedFile::fake()->image('logo.png', 440, 140),
            'favicon_path' => UploadedFile::fake()->image('icon.png', 64, 64),
            'theme_pattern_path' => UploadedFile::fake()->image('pattern.png', 128, 128),
        ])->call('save')->assertHasNoFormErrors();

        $profile->refresh();
        $this->assertSame('#156538', $profile->theme_primary_color);
        $this->assertSame('serif', $profile->theme_font);
        Storage::disk('public')->assertExists([$profile->logo_path, $profile->favicon_path, $profile->theme_pattern_path]);
        $this->assertStringContainsString($profile->favicon_path, $profile->favicon_url);
    }

    public function test_invalid_theme_values_are_rejected(): void
    {
        $profile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());
        $this->actingAs(User::factory()->developer()->create());
        Livewire::test(EditCompanyProfile::class, ['record' => $profile->id])
            ->fillForm(['theme_primary_color' => 'red; color:black', 'theme_font' => 'untrusted-font'])
            ->call('save')->assertHasFormErrors(['theme_primary_color', 'theme_font']);
        $this->assertSame('#fed700', $profile->fresh()->theme_primary_color);
    }

    public function test_brand_styles_are_rendered_across_public_pages(): void
    {
        CompanyProfile::query()->create([
            ...CompanyProfile::defaultAttributes(), 'theme_primary_color' => '#156538',
            'theme_secondary_color' => '#a84a17', 'theme_background_color' => '#fff9ed',
            'theme_font' => 'serif', 'theme_pattern_path' => 'company/patterns/pattern.png',
        ]);
        foreach (['/', '/?browse=1', '/login', '/about-us', '/contact', '/cart', '/wishlist'] as $path) {
            $this->get($path)->assertOk()->assertSee('--brand-primary: #156538;', false)
                ->assertSee('--brand-secondary: #a84a17;', false)->assertSee('company/patterns/pattern.png', false);
        }
        $this->assertSame('#ffffff', CompanyProfile::query()->first()->theme()['on_primary']);
    }

    public function test_unconfigured_and_invalid_theme_values_have_safe_defaults(): void
    {
        $profile = CompanyProfile::make(['theme_primary_color' => '</style><script>alert(1)</script>', 'theme_font' => 'untrusted']);
        $this->assertSame('#fed700', $profile->theme()['primary']);
        $this->assertSame('#000000', $profile->theme()['on_primary']);
        $this->assertStringNotContainsString('untrusted', $profile->theme()['font']);
        $this->get('/login')->assertOk()->assertSee('--brand-primary: #fed700;', false);
    }

    public function test_admin_and_superadmin_cannot_create_or_assign_developer_role(): void
    {
        $buyer = User::factory()->buyer()->create();
        foreach ([UserRole::Admin, UserRole::SuperAdmin] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]));
            $this->assertArrayNotHasKey('developer', UserResource::assignableRoles());
            Livewire::test(CreateUser::class)->fillForm([
                'name' => 'Attempt', 'email' => $role->value.'-attempt@example.test', 'phone' => '081234560001',
                'role' => 'developer', 'is_active' => true,
                'password' => 'StrongPassword123!', 'passwordConfirmation' => 'StrongPassword123!',
            ])->call('create')->assertHasFormErrors(['role']);
            Livewire::test(EditUser::class, ['record' => $buyer->id])
                ->fillForm(['role' => 'developer'])->call('save')->assertHasFormErrors(['role']);
        }
        $this->assertSame(UserRole::Buyer, $buyer->fresh()->role);
        $this->assertDatabaseCount('users', 3);
    }

    public function test_non_developer_cannot_edit_delete_or_reset_developer_account(): void
    {
        $developer = User::factory()->developer()->create();
        foreach ([UserRole::Admin, UserRole::SuperAdmin] as $role) {
            $actor = User::factory()->create(['role' => $role]);
            $this->actingAs($actor);
            $this->assertFalse(Gate::forUser($actor)->allows('update', $developer));
            $this->assertFalse(UserResource::canDelete($developer));
            $this->get('/management/users/'.$developer->id.'/edit')->assertNotFound();
            $this->get('/management/users')->assertOk()->assertDontSeeText($developer->email);
        }
        $this->assertDatabaseHas('users', ['id' => $developer->id, 'role' => 'developer']);
    }

    public function test_an_open_form_cannot_save_after_developer_role_is_revoked(): void
    {
        $profile = CompanyProfile::query()->create(CompanyProfile::defaultAttributes());
        $developer = User::factory()->developer()->create();
        $this->actingAs($developer);
        $form = Livewire::test(EditCompanyProfile::class, ['record' => $profile->id]);
        $developer->update(['role' => UserRole::Admin]);
        $form->fillForm(['theme_primary_color' => '#112233'])->call('save')->assertForbidden();
        $this->assertSame('#fed700', $profile->fresh()->theme_primary_color);
    }

    public function test_developer_can_manage_other_developers_and_cannot_delete_self(): void
    {
        $actor = User::factory()->developer()->create();
        $other = User::factory()->developer()->create();
        $this->actingAs($actor);
        $this->assertArrayHasKey('developer', UserResource::assignableRoles());
        $this->assertTrue(UserResource::canEdit($other));
        $this->assertTrue(UserResource::canDelete($other));
        $this->assertFalse(UserResource::canDelete($actor));
    }

    public function test_developer_can_be_created_through_interactive_server_command(): void
    {
        $this->artisan('store:create-developer', ['email' => 'developer@example.test'])
            ->expectsQuestion('Nama developer', 'Developer Test')
            ->expectsQuestion('Nomor HP developer', '081234567899')
            ->expectsQuestion('Password developer (minimal 12 karakter)', 'UniqueDeveloper123!')
            ->expectsQuestion('Ulangi password developer', 'UniqueDeveloper123!')
            ->assertSuccessful();
        $developer = User::query()->where('email', 'developer@example.test')->firstOrFail();
        $this->assertTrue($developer->canManageBranding());
        $this->assertTrue(Hash::check('UniqueDeveloper123!', $developer->password));
    }
}
