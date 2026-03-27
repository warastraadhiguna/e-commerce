<?php

namespace App\Filament\Resources\MeasurementUnits;

use App\Filament\Resources\Concerns\RestrictsDeleteToSuperAdmin;
use App\Filament\Resources\MeasurementUnits\Pages\CreateMeasurementUnit;
use App\Filament\Resources\MeasurementUnits\Pages\EditMeasurementUnit;
use App\Filament\Resources\MeasurementUnits\Pages\ListMeasurementUnits;
use App\Filament\Resources\MeasurementUnits\Schemas\MeasurementUnitForm;
use App\Filament\Resources\MeasurementUnits\Tables\MeasurementUnitsTable;
use App\Models\MeasurementUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MeasurementUnitResource extends Resource
{
    use RestrictsDeleteToSuperAdmin;

    protected static ?string $model = MeasurementUnit::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Satuan';

    protected static ?string $pluralModelLabel = 'Satuan';

    protected static ?string $navigationLabel = 'Satuan';

    protected static string|UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 13;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    public static function form(Schema $schema): Schema
    {
        return MeasurementUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MeasurementUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMeasurementUnits::route('/'),
            'create' => CreateMeasurementUnit::route('/create'),
            'edit' => EditMeasurementUnit::route('/{record}/edit'),
        ];
    }
}
