<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
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
                        TextEntry::make('role')
                            ->badge()
                            ->formatStateUsing(fn (UserRole|string $state): string => $state instanceof UserRole ? $state->label() : UserRole::from($state)->label())
                            ->color(fn (UserRole|string $state): string => $state instanceof UserRole ? $state->color() : UserRole::from($state)->color()),
                        TextEntry::make('email_verified_at')
                            ->label('Email verified at')
                            ->dateTime()
                            ->placeholder('Not verified'),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
            ]);
    }
}
