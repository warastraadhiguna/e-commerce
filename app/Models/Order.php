<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_number',
    'user_id',
    'shipping_address_id',
    'status',
    'customer_name',
    'customer_email',
    'customer_phone',
    'customer_role',
    'uses_partner_pricing',
    'shipping_label',
    'shipping_recipient_name',
    'shipping_phone',
    'shipping_address_line',
    'shipping_district',
    'shipping_city',
    'shipping_province',
    'shipping_postal_code',
    'shipping_notes',
    'item_count',
    'total_quantity',
    'subtotal_amount',
    'total_amount',
    'user_notes',
    'internal_notes',
    'checked_out_at',
])]
class Order extends Model
{
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'uses_partner_pricing' => 'boolean',
            'item_count' => 'integer',
            'total_quantity' => 'decimal:3',
            'subtotal_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'checked_out_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->orderBy('id');
    }

    public function getSubtotalLabelAttribute(): string
    {
        return Product::formatCurrency((float) $this->subtotal_amount);
    }

    public function getTotalLabelAttribute(): string
    {
        return Product::formatCurrency((float) $this->total_amount);
    }

    public function getTotalQuantityLabelAttribute(): string
    {
        return rtrim(rtrim(number_format((float) $this->total_quantity, 3, '.', ''), '0'), '.');
    }

    public function getShippingFullAddressAttribute(): string
    {
        return collect([
            $this->shipping_address_line,
            $this->shipping_district,
            $this->shipping_city,
            $this->shipping_province,
            $this->shipping_postal_code,
        ])->filter()->join(', ');
    }
}
