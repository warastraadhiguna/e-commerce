<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    protected static function hasProfileFields(?string $role): bool
    {
        return in_array($role, [UserRole::Buyer->value, UserRole::Mitra->value], true);
    }

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
                        TextInput::make('phone')
                            ->label('Nomor HP')
                            ->tel()
                            ->required()
                            ->maxLength(30)
                            ->unique(ignoreRecord: true)
                            ->helperText('Gunakan nomor HP aktif. Login publik memakai nomor ini.'),
                        Select::make('role')
                            ->options(fn (): array => UserResource::assignableRoles())
                            ->rules([fn () => Rule::in(array_keys(UserResource::assignableRoles()))])
                            ->default(UserRole::Buyer->value)
                            ->required()
                            ->native(false)
                            ->disabled(fn (?User $record): bool => $record?->id === auth()->id()),
                        Toggle::make('is_active')
                            ->label('Akun aktif')
                            ->disabled(fn (?User $record): bool => $record?->id === auth()->id())
                            ->default(true),
                    ]),
                Section::make('Profil pengguna')
                    ->columns(2)
                    ->visible(fn (Get $get): bool => self::hasProfileFields($get('role')))
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Nama usaha / instansi')
                            ->maxLength(255),
                        TextInput::make('contact_person')
                            ->label('PIC')
                            ->maxLength(255),
                        Textarea::make('address')
                            ->label('Alamat utama')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Data mitra')
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('role') === UserRole::Mitra->value)
                    ->schema([
                        TextInput::make('purchase_turnover_total')
                            ->label('Total omzet pembelian')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        TextInput::make('minimum_turnover_for_discount')
                            ->label('Ambang omzet diskon')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        Select::make('partner_discount_status')
                            ->label('Status approval diskon')
                            ->options(PartnerDiscountStatus::options())
                            ->default(PartnerDiscountStatus::Pending->value)
                            ->required()
                            ->native(false),
                        DateTimePicker::make('partner_discount_approved_at')
                            ->label('Tanggal approval')
                            ->seconds(false)
                            ->native(false),
                        TextInput::make('partner_discount_approved_by_name')
                            ->label('Disetujui oleh')
                            ->formatStateUsing(fn (?User $record): string => $record?->partnerDiscountApprover?->name ?? '-')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('partner_discount_notes')
                            ->label('Catatan approval')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Security')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->autocomplete('new-password')
                            ->afterStateHydrated(fn (TextInput $component): TextInput => $component->state(''))
                            ->live()
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
