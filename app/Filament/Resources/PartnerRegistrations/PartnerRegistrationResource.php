<?php

namespace App\Filament\Resources\PartnerRegistrations;

use App\Filament\Resources\PartnerRegistrations\Pages\EditPartnerRegistration;
use App\Filament\Resources\PartnerRegistrations\Pages\ListPartnerRegistrations;
use App\Filament\Resources\PartnerRegistrations\Pages\ViewPartnerRegistration;
use App\Filament\Resources\PartnerRegistrations\Schemas\PartnerRegistrationForm;
use App\Filament\Resources\PartnerRegistrations\Schemas\PartnerRegistrationInfolist;
use App\Filament\Resources\PartnerRegistrations\Tables\PartnerRegistrationsTable;
use App\Models\PartnerRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartnerRegistrationResource extends Resource
{
    protected static ?string $model = PartnerRegistration::class;

    protected static ?string $recordTitleAttribute = 'full_name';

    protected static ?string $modelLabel = 'Pendaftaran Mitra';

    protected static ?string $pluralModelLabel = 'Pendaftaran Mitra';

    protected static ?string $navigationLabel = 'Pendaftaran Mitra';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function form(Schema $schema): Schema
    {
        return PartnerRegistrationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartnerRegistrationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerRegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartnerRegistrations::route('/'),
            'view' => ViewPartnerRegistration::route('/{record}'),
            'edit' => EditPartnerRegistration::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
