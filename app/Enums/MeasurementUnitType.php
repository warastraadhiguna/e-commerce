<?php

namespace App\Enums;

enum MeasurementUnitType: string
{
    case Unit = 'unit';
    case Packaging = 'packaging';
    case Weight = 'weight';
    case Length = 'length';
    case Volume = 'volume';
    case Area = 'area';

    public function label(): string
    {
        return match ($this) {
            self::Unit => 'Unit',
            self::Packaging => 'Kemasan',
            self::Weight => 'Berat',
            self::Length => 'Panjang',
            self::Volume => 'Volume',
            self::Area => 'Luas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Unit => 'gray',
            self::Packaging => 'info',
            self::Weight => 'success',
            self::Length => 'warning',
            self::Volume => 'primary',
            self::Area => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type): array => [$type->value => $type->label()])
            ->all();
    }
}
