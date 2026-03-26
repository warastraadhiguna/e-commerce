<?php

namespace App\Filament\Resources\HomeMedia;

use App\Filament\Resources\HomeMedia\Pages\EditHomeMedia;
use App\Filament\Resources\HomeMedia\Pages\ListHomeMedia;
use App\Filament\Resources\HomeMedia\Schemas\HomeMediaForm;
use App\Filament\Resources\HomeMedia\Tables\HomeMediaTable;
use App\Models\HomeMedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HomeMediaResource extends Resource
{
    protected static ?string $model = HomeMedia::class;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $modelLabel = 'Media Home';

    protected static ?string $pluralModelLabel = 'Media Home';

    protected static ?string $navigationLabel = 'Media Home';

    protected static string|UnitEnum|null $navigationGroup = 'Company Setting';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function form(Schema $schema): Schema
    {
        return HomeMediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomeMediaTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomeMedia::route('/'),
            'edit' => EditHomeMedia::route('/{record}/edit'),
        ];
    }
}
