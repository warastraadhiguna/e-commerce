<?php

namespace App\Support;

use App\Models\CompanyProfile;

class BrandTheme
{
    public static function fonts(): array
    {
        return ['default' => 'Bawaan (Rubik)', 'system' => 'Modern (font sistem)', 'serif' => 'Klasik (Georgia)', 'rounded' => 'Santai (Trebuchet MS)'];
    }

    public static function fromProfile(CompanyProfile $profile): array
    {
        $primary = self::hex($profile->theme_primary_color, '#fed700');
        $secondary = self::hex($profile->theme_secondary_color, '#0363cd');
        $font = match ($profile->theme_font) {
            'system' => 'system-ui, -apple-system, "Segoe UI", sans-serif',
            'serif' => 'Georgia, "Times New Roman", serif',
            'rounded' => '"Trebuchet MS", Arial, sans-serif',
            default => '"Rubik", Arial, sans-serif',
        };

        return [
            'primary' => $primary,
            'secondary' => $secondary,
            'background' => self::hex($profile->theme_background_color, '#ffffff'),
            'primary_rgb' => self::rgb($primary),
            'on_primary' => self::foreground($primary),
            'on_secondary' => self::foreground($secondary),
            'font' => $font,
        ];
    }

    private static function hex(?string $value, string $fallback): string
    {
        return is_string($value) && preg_match('/^#[0-9a-f]{6}$/i', $value) ? strtolower($value) : $fallback;
    }

    private static function rgb(string $hex): string
    {
        return implode(', ', [hexdec(substr($hex, 1, 2)), hexdec(substr($hex, 3, 2)), hexdec(substr($hex, 5, 2))]);
    }

    private static function foreground(string $hex): string
    {
        $linear = array_map(function (string $value): float {
            $channel = ((float) $value) / 255;

            return $channel <= .04045 ? $channel / 12.92 : (($channel + .055) / 1.055) ** 2.4;
        }, explode(', ', self::rgb($hex)));
        $luminance = .2126 * $linear[0] + .7152 * $linear[1] + .0722 * $linear[2];

        return ($luminance + .05) / .05 >= 1.05 / ($luminance + .05) ? '#000000' : '#ffffff';
    }
}
