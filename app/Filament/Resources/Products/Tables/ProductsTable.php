<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductType;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->square(),
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->badge()
                    ->sortable(),
                TextColumn::make('productType.name')
                    ->label('Tipe')
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->toggleable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->formatStateUsing(fn (string $state, Product $record): string => rtrim(rtrim(number_format((float) $state, 3, '.', ''), '0'), '.').' '.$record->measurementUnit->symbol)
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_new_arrival')
                    ->label('New')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_bestseller')
                    ->label('Best')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('published_at')
                    ->label('Publish')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->options(Brand::query()->orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('product_type_id')
                    ->label('Tipe')
                    ->options(ProductType::query()->orderBy('name')->pluck('name', 'id')),
                TernaryFilter::make('is_active')
                    ->label('Aktif')
                    ->nullable()
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->nullable()
                    ->trueLabel('Featured')
                    ->falseLabel('Bukan featured'),
                TernaryFilter::make('is_new_arrival')
                    ->label('New arrival')
                    ->nullable()
                    ->trueLabel('New arrival')
                    ->falseLabel('Bukan new arrival'),
                TernaryFilter::make('is_bestseller')
                    ->label('Bestseller')
                    ->nullable()
                    ->trueLabel('Bestseller')
                    ->falseLabel('Bukan bestseller'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
