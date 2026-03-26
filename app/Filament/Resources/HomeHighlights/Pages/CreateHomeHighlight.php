<?php

namespace App\Filament\Resources\HomeHighlights\Pages;

use App\Filament\Resources\HomeHighlights\HomeHighlightResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateHomeHighlight extends CreateRecord
{
    protected static string $resource = HomeHighlightResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
