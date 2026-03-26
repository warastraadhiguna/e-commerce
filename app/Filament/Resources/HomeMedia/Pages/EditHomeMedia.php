<?php

namespace App\Filament\Resources\HomeMedia\Pages;

use App\Filament\Resources\HomeMedia\HomeMediaResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditHomeMedia extends EditRecord
{
    protected static string $resource = HomeMediaResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
