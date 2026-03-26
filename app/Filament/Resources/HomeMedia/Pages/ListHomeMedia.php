<?php

namespace App\Filament\Resources\HomeMedia\Pages;

use App\Filament\Resources\HomeMedia\HomeMediaResource;
use Filament\Resources\Pages\ListRecords;

class ListHomeMedia extends ListRecords
{
    protected static string $resource = HomeMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
