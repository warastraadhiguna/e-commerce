<?php

namespace App\Filament\Resources\MeasurementUnits\Schemas;

use App\Enums\MeasurementUnitType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MeasurementUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi satuan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama satuan')
                            ->required()
                            ->maxLength(80)
                            ->unique(ignoreRecord: true),
                        TextInput::make('symbol')
                            ->label('Simbol')
                            ->required()
                            ->maxLength(20),
                        Select::make('measurement_type')
                            ->label('Jenis pengukuran')
                            ->options(MeasurementUnitType::options())
                            ->required()
                            ->native(false),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                        Toggle::make('allow_decimal')
                            ->label('Boleh desimal')
                            ->default(false),
                        TextInput::make('decimal_places')
                            ->label('Presisi desimal')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(6)
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
