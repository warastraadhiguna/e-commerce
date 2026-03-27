<?php

namespace App\Filament\Widgets;

use App\Enums\PartnerRegistrationStatus;
use App\Enums\UserRole;
use App\Filament\Resources\PartnerRegistrations\PartnerRegistrationResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\PartnerRegistration;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ManagementOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Dashboard';

    protected ?string $description = 'Pantau mitra baru dan komposisi user langsung dari dashboard.';

    protected function getStats(): array
    {
        $pendingPartnerRegistrations = PartnerRegistration::query()
            ->where('status', PartnerRegistrationStatus::Pending)
            ->count();

        $buyerCount = User::query()
            ->where('role', UserRole::Buyer)
            ->count();

        $mitraCount = User::query()
            ->where('role', UserRole::Mitra)
            ->count();

        return [
            Stat::make('Mitra Baru', number_format($pendingPartnerRegistrations))
                ->description('Pendaftaran mitra yang belum ditanggapi')
                ->descriptionIcon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color($pendingPartnerRegistrations > 0 ? 'warning' : 'success')
                ->icon(Heroicon::OutlinedClipboardDocumentList)
                ->url(PartnerRegistrationResource::getUrl('index')),
            Stat::make('User Buyer', number_format($buyerCount))
                ->description('Lihat daftar buyer yang sudah terdaftar')
                ->descriptionIcon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('info')
                ->icon(Heroicon::OutlinedUserGroup)
                ->url(UserResource::getUrl('index', [
                    'tableFilters' => [
                        'role' => [
                            'value' => UserRole::Buyer->value,
                        ],
                    ],
                ])),
            Stat::make('User Mitra', number_format($mitraCount))
                ->description('Lihat data user mitra dan statusnya')
                ->descriptionIcon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('warning')
                ->icon(Heroicon::OutlinedBuildingOffice)
                ->url(UserResource::getUrl('index', [
                    'tableFilters' => [
                        'role' => [
                            'value' => UserRole::Mitra->value,
                        ],
                    ],
                ])),
        ];
    }
}
