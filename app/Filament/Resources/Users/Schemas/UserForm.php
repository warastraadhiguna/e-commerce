<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('role')
                            ->options(UserRole::options())
                            ->default(UserRole::Customer->value)
                            ->required()
                            ->native(false)
                            ->disabled(fn (?User $record): bool => $record?->id === auth()->id()),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email verified at')
                            ->native(false)
                            ->seconds(false),
                    ]),
                Section::make('Security')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->autocomplete('new-password')
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->rule(Password::defaults())
                            ->showAllValidationMessages()
                            ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Leave blank to keep the current password.' : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->same('passwordConfirmation'),
                        TextInput::make('passwordConfirmation')
                            ->label('Password confirmation')
                            ->password()
                            ->autocomplete('new-password')
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required(fn (Get $get, string $operation): bool => $operation === 'create' || filled($get('password')))
                            ->visible(fn (Get $get, string $operation): bool => $operation === 'create' || filled($get('password')))
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
