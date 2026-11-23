<?php

namespace App\Filament\Resources\CompanyProfiles\Pages;

use App\Filament\Resources\CompanyProfiles\CompanyProfileResource;
use App\Models\CompanyProfile;
use Filament\Resources\Pages\ListRecords;

class ListCompanyProfiles extends ListRecords
{
    protected static string $resource = CompanyProfileResource::class;

    public function mount(): void
    {
        abort_unless(CompanyProfileResource::canViewAny(), 403);

        $record = CompanyProfile::query()->first()
            ?? CompanyProfile::query()->create(CompanyProfile::defaultAttributes());

        $this->redirect(CompanyProfileResource::getUrl('edit', ['record' => $record]));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
