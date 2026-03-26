<?php

namespace App\Filament\Resources\HomeHighlights\Pages;

use App\Filament\Resources\HomeHighlights\HomeHighlightResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditHomeHighlight extends EditRecord
{
    protected static string $resource = HomeHighlightResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
