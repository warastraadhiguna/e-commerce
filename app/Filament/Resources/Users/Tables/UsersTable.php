<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label('Nomor HP')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),
                TextColumn::make('business_name')
                    ->label('Usaha / instansi')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact_person')
                    ->label('PIC')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('role')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (UserRole|string $state): string => $state instanceof UserRole ? $state->label() : UserRole::from($state)->label())
                    ->color(fn (UserRole|string $state): string => $state instanceof UserRole ? $state->color() : UserRole::from($state)->color()),
                TextColumn::make('partner_discount_status')
                    ->label('Approval mitra')
                    ->badge()
                    ->formatStateUsing(fn (PartnerDiscountStatus|string|null $state): string => blank($state) ? '-' : ($state instanceof PartnerDiscountStatus ? $state->label() : PartnerDiscountStatus::from($state)->label()))
                    ->color(fn (PartnerDiscountStatus|string|null $state): string => blank($state) ? 'gray' : ($state instanceof PartnerDiscountStatus ? $state->color() : PartnerDiscountStatus::from($state)->color()))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(UserRole::options()),
                SelectFilter::make('partner_discount_status')
                    ->label('Approval mitra')
                    ->options(PartnerDiscountStatus::options()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (User $record): bool => $record->id === auth()->id()),
            ]);
    }
}
