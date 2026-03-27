<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('checked_out_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_phone')
                    ->label('Nomor HP')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('customer_role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => blank($state) ? '-' : UserRole::from($state)->label())
                    ->color(fn (?string $state): string => blank($state) ? 'gray' : UserRole::from($state)->color()),
                IconColumn::make('uses_partner_pricing')
                    ->label('Harga Mitra')
                    ->boolean(),
                TextColumn::make('shipping_city')
                    ->label('Kota')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('item_count')
                    ->label('Item')
                    ->sortable(),
                TextColumn::make('total_quantity')
                    ->label('Qty')
                    ->formatStateUsing(fn (?string $state): string => rtrim(rtrim(number_format((float) $state, 3, '.', ''), '0'), '.'))
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn (?string $state): string => \App\Models\Product::formatCurrency((float) $state))
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus|string $state): string => $state instanceof OrderStatus ? $state->label() : OrderStatus::from($state)->label())
                    ->color(fn (OrderStatus|string $state): string => $state instanceof OrderStatus ? $state->color() : OrderStatus::from($state)->color())
                    ->sortable(),
                TextColumn::make('checked_out_at')
                    ->label('Checkout')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(OrderStatus::options()),
                SelectFilter::make('customer_role')
                    ->label('Role customer')
                    ->options(UserRole::options()),
                SelectFilter::make('uses_partner_pricing')
                    ->label('Tipe harga')
                    ->options([
                        '1' => 'Harga mitra',
                        '0' => 'Harga buyer',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (($data['value'] ?? null) === null || $data['value'] === '') {
                            return $query;
                        }

                        return $query->where('uses_partner_pricing', $data['value'] === '1');
                    }),
                Filter::make('checked_out_at')
                    ->label('Tanggal checkout')
                    ->schema([
                        DatePicker::make('checked_out_from')
                            ->label('Dari tanggal'),
                        DatePicker::make('checked_out_until')
                            ->label('Sampai tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['checked_out_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('checked_out_at', '>=', $date)
                            )
                            ->when(
                                $data['checked_out_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('checked_out_at', '<=', $date)
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
