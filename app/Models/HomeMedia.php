<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'key',
    'label',
    'section',
    'image_path',
    'link_url',
    'sort_order',
])]
class HomeMedia extends Model
{
    public function getLinkHrefAttribute(): string
    {
        $link = trim((string) $this->link_url);

        if ($link === '' || $link === '#' || preg_match('/^[^:?]*\.html(?:[?#].*)?$/i', $link)) {
            return route('home', ['catalog' => 'all', 'browse' => 1]).'#search-results';
        }

        return $link;
    }

    /**
     * @return array<string, array{name: string, size: string, aspect_ratio: string, width: int, height: int}>
     */
    public static function slotConfigurations(): array
    {
        return [
            'hero_slider_1' => [
                'name' => 'Banner Utama Depan 1',
                'size' => '780 x 480 px',
                'aspect_ratio' => '13:8',
                'width' => 780,
                'height' => 480,
            ],
            'hero_slider_2' => [
                'name' => 'Banner Utama Depan 2',
                'size' => '780 x 480 px',
                'aspect_ratio' => '13:8',
                'width' => 780,
                'height' => 480,
            ],
            'hero_slider_3' => [
                'name' => 'Banner Utama Depan 3',
                'size' => '780 x 480 px',
                'aspect_ratio' => '13:8',
                'width' => 780,
                'height' => 480,
            ],
            'side_banner_1' => [
                'name' => 'Banner Utama Atas',
                'size' => '370 x 230 px',
                'aspect_ratio' => '37:23',
                'width' => 370,
                'height' => 230,
            ],
            'side_banner_2' => [
                'name' => 'Banner Utama Bawah',
                'size' => '370 x 230 px',
                'aspect_ratio' => '37:23',
                'width' => 370,
                'height' => 230,
            ],
            'promo_banner_1' => [
                'name' => 'Banner Promo 1',
                'size' => '376 x 230 px',
                'aspect_ratio' => '188:115',
                'width' => 376,
                'height' => 230,
            ],
            'promo_banner_2' => [
                'name' => 'Banner Promo 2',
                'size' => '376 x 230 px',
                'aspect_ratio' => '188:115',
                'width' => 376,
                'height' => 230,
            ],
            'promo_banner_3' => [
                'name' => 'Banner Promo 3',
                'size' => '377 x 230 px',
                'aspect_ratio' => '377:230',
                'width' => 377,
                'height' => 230,
            ],
            'featured_banner_1' => [
                'name' => 'Banner Lebar Bawah',
                'size' => '1170 x 400 px',
                'aspect_ratio' => '117:40',
                'width' => 1170,
                'height' => 400,
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function sectionLabels(): array
    {
        return [
            'hero_slider' => 'Banner Utama Depan',
            'side_banner' => 'Banner Utama Samping',
            'promo_banner' => 'Banner Promo Tengah',
            'featured_banner' => 'Banner Lebar Bawah',
        ];
    }

    /**
     * @return array{name: string, size: string, aspect_ratio: string, width: int, height: int}|null
     */
    public function slotConfiguration(): ?array
    {
        return static::slotConfigurations()[$this->key] ?? null;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
