<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id',
    'product_id',
    'product_sku',
    'product_slug',
    'product_name',
    'brand_name',
    'category_name',
    'measurement_summary',
    'quantity',
    'unit_price',
    'line_total',
])]
class OrderItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getQuantityLabelAttribute(): string
    {
        return rtrim(rtrim(number_format((float) $this->quantity, 3, '.', ''), '0'), '.');
    }

    public function getUnitPriceLabelAttribute(): string
    {
        return Product::formatCurrency((float) $this->unit_price);
    }

    public function getLineTotalLabelAttribute(): string
    {
        return Product::formatCurrency((float) $this->line_total);
    }
}
