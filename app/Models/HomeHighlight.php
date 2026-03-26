<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'description',
    'image_path',
    'fallback_asset',
    'sort_order',
    'is_active',
])]
class HomeHighlight extends Model
{
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
                'title' => 'Free Delivery',
                'description' => 'Gratis pengiriman dan pengembalian. Lihat detail checkout untuk estimasi waktu kirim.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Safe Payment',
                'description' => 'Bayar dengan metode pembayaran populer yang aman dan tepercaya.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Shop with Confidence',
                'description' => 'Perlindungan pembeli membantu transaksi lebih nyaman dari pemesanan hingga barang diterima.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => '24/7 Help Center',
                'description' => 'Punya pertanyaan? Tim kami siap membantu kapan saja.',
                'image_path' => null,
                'fallback_asset' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (filled($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }

        return null;
    }
}
