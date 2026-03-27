<?php

namespace App\Filament\Resources\MeasurementUnits\Tables;

use App\Enums\MeasurementUnitType;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MeasurementUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('symbol')
                    ->label('Simbol')
                    ->badge()
                    ->copyable(),
                TextColumn::make('measurement_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (MeasurementUnitType|string $state): string => $state instanceof MeasurementUnitType ? $state->label() : MeasurementUnitType::from($state)->label())
                    ->color(fn (MeasurementUnitType|string $state): string => $state instanceof MeasurementUnitType ? $state->color() : MeasurementUnitType::from($state)->color()),
                IconColumn::make('allow_decimal')
                    ->label('Desimal')
                    ->boolean(),
                TextColumn::make('decimal_places')
                    ->label('Presisi'),
                TextColumn::make('products_count')
                    ->label('Produk')
                    ->counts('products'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('measurement_type')
                    ->label('Jenis')
                    ->options(MeasurementUnitType::options()),
                TernaryFilter::make('is_active')
                    ->label('Aktif')
                    ->nullable()
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
