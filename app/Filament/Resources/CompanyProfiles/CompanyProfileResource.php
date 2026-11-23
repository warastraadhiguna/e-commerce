<?php

namespace App\Filament\Resources\CompanyProfiles;

use App\Filament\Resources\CompanyProfiles\Pages\EditCompanyProfile;
use App\Filament\Resources\CompanyProfiles\Pages\ListCompanyProfiles;
use App\Filament\Resources\CompanyProfiles\Schemas\CompanyProfileForm;
use App\Filament\Resources\CompanyProfiles\Tables\CompanyProfilesTable;
use App\Models\CompanyProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompanyProfileResource extends Resource
{
    protected static ?string $model = CompanyProfile::class;

    protected static ?string $recordTitleAttribute = 'company_name';

    protected static ?string $modelLabel = 'Identitas & Tema';

    protected static ?string $pluralModelLabel = 'Identitas & Tema';

    protected static ?string $navigationLabel = 'Identitas & Tema';

    protected static string|UnitEnum|null $navigationGroup = 'Developer';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    public static function form(Schema $schema): Schema
    {
        return CompanyProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyProfilesTable::configure($table);
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
            'index' => ListCompanyProfiles::route('/'),
            'edit' => EditCompanyProfile::route('/{record}/edit'),
        ];
    }
}
