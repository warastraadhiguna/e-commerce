<?php

namespace App\Filament\Resources\PartnerRegistrations\Tables;

use App\Enums\PartnerRegistrationStatus;
use App\Models\PartnerRegistration;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnerRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('whatsapp')
                    ->label('No. WhatsApp')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (PartnerRegistrationStatus|string $state): string => $state instanceof PartnerRegistrationStatus ? $state->label() : PartnerRegistrationStatus::from($state)->label())
                    ->color(fn (PartnerRegistrationStatus|string $state): string => $state instanceof PartnerRegistrationStatus ? $state->color() : PartnerRegistrationStatus::from($state)->color()),
                TextColumn::make('respondedBy.name')
                    ->label('Ditanggapi oleh')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('responded_at')
                    ->label('Waktu ditanggapi')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PartnerRegistrationStatus::options()),
            ])
            ->recordActions([
                Action::make('markResponded')
                    ->label('Tandai ditanggapi')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn (PartnerRegistration $record): bool => $record->isResponded())
                    ->action(function (PartnerRegistration $record): void {
                        $record->update([
                            'status' => PartnerRegistrationStatus::Responded,
                            'responded_at' => now(),
                            'responded_by' => auth()->id(),
                        ]);
                    }),
                Action::make('markPending')
                    ->label('Tandai belum ditanggapi')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->hidden(fn (PartnerRegistration $record): bool => ! $record->isResponded())
                    ->action(function (PartnerRegistration $record): void {
                        $record->update([
                            'status' => PartnerRegistrationStatus::Pending,
                            'responded_at' => null,
                            'responded_by' => null,
                        ]);
                    }),
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
