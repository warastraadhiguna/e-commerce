<?php

namespace App\Filament\Resources\PartnerRegistrations\Schemas;

use App\Enums\PartnerRegistrationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data pendaftaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nama lengkap')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('whatsapp')
                            ->label('No. WhatsApp')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('message')
                            ->label('Keterangan')
                            ->rows(6)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Tanggapan admin')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(PartnerRegistrationStatus::options())
                            ->required()
                            ->native(false),
                        TextInput::make('respondedBy.name')
                            ->label('Ditanggapi oleh')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('admin_notes')
                            ->label('Catatan admin')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        TextInput::make('ip_address')
                            ->label('IP address')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('responded_at')
                            ->label('Waktu ditanggapi')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('user_agent')
                            ->label('User agent')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
