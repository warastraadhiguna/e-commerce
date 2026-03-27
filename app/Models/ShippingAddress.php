<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'label',
    'recipient_name',
    'phone',
    'address_line',
    'district',
    'city',
    'province',
    'postal_code',
    'notes',
    'is_default',
    'sort_order',
])]
class ShippingAddress extends Model
{
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code,
        ])->filter()->join(', ');
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => User::normalizePhone($value),
        );
    }
}
