<?php

namespace App\Filament\Resources\MeasurementUnits\Pages;

use App\Filament\Resources\MeasurementUnits\MeasurementUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMeasurementUnits extends ListRecords
{
    protected static string $resource = MeasurementUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
