<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    protected static function hasProfileFields($record): bool
    {
        return $record?->isBuyer() || $record?->isMitra();
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->copyable(),
                        TextEntry::make('phone')
                            ->label('Nomor HP')
                            ->copyable()
                            ->placeholder('-'),
                        TextEntry::make('role')
                            ->badge()
                            ->formatStateUsing(fn (UserRole|string $state): string => $state instanceof UserRole ? $state->label() : UserRole::from($state)->label())
                            ->color(fn (UserRole|string $state): string => $state instanceof UserRole ? $state->color() : UserRole::from($state)->color()),
                        TextEntry::make('is_active')
                            ->label('Akun aktif')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                    ]),
                Section::make('Profil pengguna')
                    ->columns(2)
                    ->visible(fn ($record): bool => self::hasProfileFields($record))
                    ->schema([
                        TextEntry::make('business_name')
                            ->label('Nama usaha / instansi')
                            ->placeholder('-'),
                        TextEntry::make('contact_person')
                            ->label('PIC')
                            ->placeholder('-'),
                        TextEntry::make('address')
                            ->label('Alamat utama')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('shipping_addresses_count')
                            ->label('Alamat kirim')
                            ->state(fn ($record): string => $record->shippingAddresses()->count().' alamat')
                            ->columnSpanFull(),
                    ]),
                Section::make('Data mitra')
                    ->columns(2)
                    ->visible(fn ($record): bool => $record?->role === UserRole::Mitra)
                    ->schema([
                        TextEntry::make('purchase_turnover_total')
                            ->label('Total omzet pembelian')
                            ->money('IDR'),
                        TextEntry::make('minimum_turnover_for_discount')
                            ->label('Ambang omzet diskon')
                            ->money('IDR'),
                        TextEntry::make('partner_discount_status')
                            ->label('Status approval diskon')
                            ->badge()
                            ->formatStateUsing(fn (PartnerDiscountStatus|string $state): string => $state instanceof PartnerDiscountStatus ? $state->label() : PartnerDiscountStatus::from($state)->label())
                            ->color(fn (PartnerDiscountStatus|string $state): string => $state instanceof PartnerDiscountStatus ? $state->color() : PartnerDiscountStatus::from($state)->color()),
                        TextEntry::make('partner_discount_approved_at')
                            ->label('Tanggal approval')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('partnerDiscountApprover.name')
                            ->label('Disetujui oleh')
                            ->placeholder('-'),
                        TextEntry::make('partner_discount_notes')
                            ->label('Catatan approval')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
