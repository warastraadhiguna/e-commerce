<?php

namespace App\Filament\Resources\PartnerRegistrations\Pages;

use App\Enums\PartnerRegistrationStatus;
use App\Filament\Resources\PartnerRegistrations\PartnerRegistrationResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartnerRegistration extends EditRecord
{
    protected static string $resource = PartnerRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $status = $data['status'] instanceof PartnerRegistrationStatus
            ? $data['status']
            : PartnerRegistrationStatus::from($data['status']);

        if ($status === PartnerRegistrationStatus::Responded) {
            $data['responded_at'] ??= now();
            $data['responded_by'] ??= auth()->id();
        } else {
            $data['responded_at'] = null;
            $data['responded_by'] = null;
        }

        return $data;
    }
}
