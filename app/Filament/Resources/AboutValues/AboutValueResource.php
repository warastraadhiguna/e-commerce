<?php

namespace App\Filament\Resources\AboutValues;

use App\Filament\Resources\AboutValues\Pages\CreateAboutValue;
use App\Filament\Resources\AboutValues\Pages\EditAboutValue;
use App\Filament\Resources\AboutValues\Pages\ListAboutValues;
use App\Filament\Resources\AboutValues\Pages\ViewAboutValue;
use App\Filament\Resources\AboutValues\Schemas\AboutValueForm;
use App\Filament\Resources\AboutValues\Schemas\AboutValueInfolist;
use App\Filament\Resources\AboutValues\Tables\AboutValuesTable;
use App\Models\AboutValue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AboutValueResource extends Resource
{
    protected static ?string $model = AboutValue::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Our Value';

    protected static ?string $pluralModelLabel = 'Our Values';

    protected static ?string $navigationLabel = 'Our Values';

    protected static string|UnitEnum|null $navigationGroup = 'Company Setting';

    protected static ?int $navigationSort = 2;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSparkles;

    public static function form(Schema $schema): Schema
    {
        return AboutValueForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AboutValueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AboutValuesTable::configure($table);
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
            'index' => ListAboutValues::route('/'),
            'create' => CreateAboutValue::route('/create'),
            'view' => ViewAboutValue::route('/{record}'),
            'edit' => EditAboutValue::route('/{record}/edit'),
        ];
    }
}
