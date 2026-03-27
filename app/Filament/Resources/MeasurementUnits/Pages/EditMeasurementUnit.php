<?php

namespace App\Filament\Resources\MeasurementUnits\Pages;

use App\Filament\Resources\MeasurementUnits\MeasurementUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMeasurementUnit extends EditRecord
{
    protected static string $resource = MeasurementUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
