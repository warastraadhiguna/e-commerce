<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'brand_id',
    'product_type_id',
    'product_category_id',
    'measurement_unit_id',
    'sku',
    'slug',
    'barcode',
    'name',
    'short_name',
    'excerpt',
    'description',
    'thumbnail_path',
    'price',
    'partner_discount_price',
    'compare_at_price',
    'cost_price',
    'stock_quantity',
    'min_order_quantity',
    'unit_value',
    'weight',
    'length',
    'width',
    'height',
    'is_active',
    'is_featured',
    'is_new_arrival',
    'is_bestseller',
    'published_at',
    'sort_order',
])]
class Product extends Model
{
    public static function formatCurrency(?float $amount): string
    {
        return 'Rp'.number_format((float) $amount, 2, ',', '.');
    }

    public function scopeActivePublished(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $builder): void {
                $builder
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'partner_discount_price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'stock_quantity' => 'decimal:3',
            'min_order_quantity' => 'decimal:3',
            'unit_value' => 'decimal:3',
            'weight' => 'decimal:3',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_bestseller' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! filled($this->thumbnail_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->thumbnail_path);
    }

    public function getUnitSummaryAttribute(): string
    {
        $value = rtrim(rtrim(number_format((float) $this->unit_value, 3, '.', ''), '0'), '.');

        return trim("{$value} {$this->measurementUnit?->symbol}");
    }

    public function getHasDiscountAttribute(): bool
    {
        return filled($this->compare_at_price) && ((float) $this->compare_at_price > (float) $this->price);
    }

    public function getHasPartnerDiscountAttribute(): bool
    {
        return filled($this->partner_discount_price) && ((float) $this->partner_discount_price > 0) && ((float) $this->partner_discount_price < (float) $this->price);
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->has_discount) {
            return null;
        }

        return (int) round((1 - ((float) $this->price / (float) $this->compare_at_price)) * 100);
    }

    public function usesPartnerPriceFor(?User $user): bool
    {
        return $this->has_partner_discount && ($user?->isEligibleForPartnerPricing() ?? false);
    }

    public function displayPriceFor(?User $user): float
    {
        return $this->usesPartnerPriceFor($user)
            ? (float) $this->partner_discount_price
            : (float) $this->price;
    }

    public function oldPriceFor(?User $user): ?float
    {
        if ($this->usesPartnerPriceFor($user)) {
            return (float) $this->price;
        }

        if ($this->has_discount) {
            return (float) $this->compare_at_price;
        }

        return null;
    }

    public function discountPercentageFor(?User $user): ?int
    {
        $oldPrice = $this->oldPriceFor($user);
        $displayPrice = $this->displayPriceFor($user);

        if (! $oldPrice || $oldPrice <= $displayPrice) {
            return null;
        }

        return (int) round((1 - ($displayPrice / $oldPrice)) * 100);
    }

    public function displayPriceLabelFor(?User $user): string
    {
        return self::formatCurrency($this->displayPriceFor($user));
    }

    public function oldPriceLabelFor(?User $user): ?string
    {
        $oldPrice = $this->oldPriceFor($user);

        return $oldPrice !== null ? self::formatCurrency($oldPrice) : null;
    }
}
