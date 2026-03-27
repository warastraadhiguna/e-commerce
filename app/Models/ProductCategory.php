<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'product_type_id',
    'parent_id',
    'name',
    'slug',
    'image_path',
    'description',
    'is_active',
    'sort_order',
])]
class ProductCategory extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function getFullNameAttribute(): string
    {
        if (! $this->relationLoaded('parent') && filled($this->parent_id)) {
            $this->load('parent');
        }

        return $this->parent
            ? "{$this->parent->full_name} / {$this->name}"
            : $this->name;
    }
}
