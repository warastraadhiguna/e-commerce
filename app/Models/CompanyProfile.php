<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'company_name',
    'phone',
    'email',
    'address',
    'description',
    'logo_path',
    'facebook_url',
    'x_url',
    'youtube_url',
    'instagram_url',
])]
class CompanyProfile extends Model
{
    public static function defaultAttributes(): array
    {
        return [
            'company_name' => config('app.name', 'E-Commerce'),
            'phone' => '(+123) 123 321 345',
            'email' => 'info@yourdomain.com',
            'address' => '6688 Princess Road, London, Greater London BAS 23JK, UK',
            'description' => 'We are a team of designers and developers that create high quality HTML Template & Woocommerce, Shopify Theme.',
            'logo_path' => null,
            'facebook_url' => null,
            'x_url' => null,
            'youtube_url' => null,
            'instagram_url' => null,
        ];
    }

    public function getHasLogoAttribute(): bool
    {
        return filled($this->logo_path);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (filled($this->logo_path)) {
            return Storage::disk('public')->url($this->logo_path);
        }

        return null;
    }

    public function getFaviconUrlAttribute(): string
    {
        return $this->logo_url
            ?? "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'/%3E";
    }

    public function getFaviconHrefAttribute(): string
    {
        if (! $this->has_logo) {
            return $this->favicon_url;
        }

        return $this->favicon_url.'?v='.($this->updated_at?->timestamp ?? '0');
    }

    /**
     * @return array<int, array{class: string, icon: ?string, title: string, url: ?string, label?: string}>
     */
    public function socialLinks(): array
    {
        return collect([
            ['class' => 'x-social', 'icon' => null, 'title' => 'X', 'url' => $this->x_url, 'label' => 'X'],
            ['class' => 'facebook', 'icon' => 'facebook', 'title' => 'Facebook', 'url' => $this->facebook_url],
            ['class' => 'youtube', 'icon' => 'youtube', 'title' => 'Youtube', 'url' => $this->youtube_url],
            ['class' => 'instagram', 'icon' => 'instagram', 'title' => 'Instagram', 'url' => $this->instagram_url],
        ])
            ->filter(fn (array $socialLink): bool => filled($socialLink['url']))
            ->values()
            ->all();
    }
}
