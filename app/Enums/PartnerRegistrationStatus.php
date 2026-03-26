<?php

namespace App\Enums;

enum PartnerRegistrationStatus: string
{
    case Pending = 'pending';
    case Responded = 'responded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Belum ditanggapi',
            self::Responded => 'Sudah ditanggapi',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Responded => 'success',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])
            ->all();
    }
}
