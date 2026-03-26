<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'icon',
    'sort_order',
    'is_active',
])]
class AboutValue extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaultItems(): array
    {
        return [
            [
                'title' => 'Fast Response',
                'description' => 'Respon cepat untuk kebutuhan pelanggan, pendaftaran mitra, dan tindak lanjut operasional.',
                'icon' => 'bolt',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Trustworthy',
                'description' => 'Proses bisnis dibangun dengan pendekatan yang aman, jelas, dan siap diaudit.',
                'icon' => 'shield',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Growth Focus',
                'description' => 'Kami menyiapkan fondasi digital yang mendukung pertumbuhan toko dan kemitraan jangka panjang.',
                'icon' => 'line-chart',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Partnership',
                'description' => 'Kami membuka ruang kolaborasi yang rapi dan mudah diakses melalui halaman pendaftaran mitra.',
                'icon' => 'users',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];
    }
}
