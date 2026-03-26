<?php

namespace App\Filament\Resources\PartnerRegistrations\Pages;

use App\Filament\Resources\PartnerRegistrations\PartnerRegistrationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnerRegistration extends ViewRecord
{
    protected static string $resource = PartnerRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
