<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'superadmin';
    case Admin = 'admin';
    case Buyer = 'buyer';
    case Mitra = 'mitra';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Superadmin',
            self::Admin => 'Admin',
            self::Buyer => 'Buyer',
            self::Mitra => 'Mitra',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SuperAdmin => 'success',
            self::Admin => 'danger',
            self::Buyer => 'gray',
            self::Mitra => 'warning',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $role): array => [$role->value => $role->label()])
            ->all();
    }
}
