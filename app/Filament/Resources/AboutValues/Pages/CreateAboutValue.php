<?php

namespace App\Filament\Resources\AboutValues\Pages;

use App\Filament\Resources\AboutValues\AboutValueResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAboutValue extends CreateRecord
{
    protected static string $resource = AboutValueResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
