<?php

namespace App\Filament\Resources\PartnerRegistrations\Schemas;

use App\Enums\PartnerRegistrationStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerRegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data pendaftaran')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('full_name')
                            ->label('Nama lengkap'),
                        TextEntry::make('whatsapp')
                            ->label('No. WhatsApp')
                            ->copyable(),
                        TextEntry::make('message')
                            ->label('Keterangan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Tanggapan admin')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (PartnerRegistrationStatus|string $state): string => $state instanceof PartnerRegistrationStatus ? $state->label() : PartnerRegistrationStatus::from($state)->label())
                            ->color(fn (PartnerRegistrationStatus|string $state): string => $state instanceof PartnerRegistrationStatus ? $state->color() : PartnerRegistrationStatus::from($state)->color()),
                        TextEntry::make('respondedBy.name')
                            ->label('Ditanggapi oleh')
                            ->placeholder('-'),
                        TextEntry::make('responded_at')
                            ->label('Waktu ditanggapi')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('admin_notes')
                            ->label('Catatan admin')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat pada')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui pada')
                            ->dateTime(),
                        TextEntry::make('ip_address')
                            ->label('IP address')
                            ->placeholder('-'),
                        TextEntry::make('user_agent')
                            ->label('User agent')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
