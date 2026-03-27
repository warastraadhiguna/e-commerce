<?php

namespace App\Models;

use App\Enums\MeasurementUnitType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'symbol',
    'measurement_type',
    'allow_decimal',
    'decimal_places',
    'description',
    'is_active',
    'sort_order',
])]
class MeasurementUnit extends Model
{
    protected function casts(): array
    {
        return [
            'measurement_type' => MeasurementUnitType::class,
            'allow_decimal' => 'boolean',
            'decimal_places' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->symbol})";
    }
}
