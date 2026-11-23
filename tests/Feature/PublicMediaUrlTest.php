<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\CompanyProfile;
use App\Models\HomeHighlight;
use App\Models\HomeMedia;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicMediaUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_media_does_not_use_a_stale_application_domain(): void
    {
        config(['app.url' => 'https://umkm.warastra-adhiguna.com']);
        Storage::forgetDisk('public');

        $this->assertSame('/storage/products/example.jpg', Product::make(['thumbnail_path' => 'products/example.jpg'])->thumbnail_url);
        $this->assertSame('/storage/home-media/example.jpg', HomeMedia::make(['image_path' => 'home-media/example.jpg'])->image_url);
        $this->assertSame('/storage/brands/example.jpg', Brand::make(['logo_path' => 'brands/example.jpg'])->logo_url);
        $this->assertSame('/storage/categories/example.jpg', ProductCategory::make(['image_path' => 'categories/example.jpg'])->image_url);
        $this->assertSame('/storage/highlights/example.jpg', HomeHighlight::make(['image_path' => 'highlights/example.jpg'])->image_url);
        $profile = CompanyProfile::make(['logo_path' => 'company/logo.jpg', 'favicon_path' => 'company/icon.png', 'theme_pattern_path' => 'company/pattern.png']);
        $this->assertSame('/storage/company/logo.jpg', $profile->logo_url);
        $this->assertSame('/storage/company/icon.png', $profile->favicon_url);
        $this->assertSame('/storage/company/pattern.png', $profile->pattern_url);
    }

    public function test_page_on_a_new_domain_renders_images_from_that_same_origin(): void
    {
        config(['app.url' => 'https://umkm.warastra-adhiguna.com']);
        Storage::forgetDisk('public');
        CompanyProfile::create([...CompanyProfile::defaultAttributes(), 'company_name' => 'Mitra Sehati', 'logo_path' => 'company/logo.jpg']);

        $this->get('https://mitrasehati-pia.warastra-adhiguna.com/')
            ->assertOk()->assertSee('src="/storage/company/logo.jpg"', false)
            ->assertDontSee('https://umkm.warastra-adhiguna.com/storage', false);
    }

    public function test_an_explicit_media_cdn_url_remains_supported(): void
    {
        config(['filesystems.disks.public.url' => 'https://cdn.example.test/media']);
        Storage::forgetDisk('public');

        $this->assertSame('https://cdn.example.test/media/products/example.jpg', Product::make(['thumbnail_path' => 'products/example.jpg'])->thumbnail_url);
    }
}
