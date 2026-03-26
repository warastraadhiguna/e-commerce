<?php

namespace App\Filament\Resources\PartnerRegistrations\Pages;

use App\Filament\Resources\PartnerRegistrations\PartnerRegistrationResource;
use Filament\Resources\Pages\ListRecords;

class ListPartnerRegistrations extends ListRecords
{
    protected static string $resource = PartnerRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
