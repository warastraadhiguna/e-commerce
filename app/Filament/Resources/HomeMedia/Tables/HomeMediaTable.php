<?php

namespace App\Filament\Resources\HomeMedia\Tables;

use App\Models\HomeMedia;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeMediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('section')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HomeMedia::sectionLabels()[$state] ?? $state),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ImageColumn::make('image_url')
                    ->label('Preview')
                    ->square(),
                TextColumn::make('link_url')
                    ->label('Link')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
