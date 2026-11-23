<?php

namespace App\Filament\Widgets;

use App\Enums\PartnerRegistrationStatus;
use App\Enums\UserRole;
use App\Filament\Pages\Finance;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\PartnerRegistrations\PartnerRegistrationResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Order;
use App\Models\PartnerRegistration;
use App\Models\Product;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ManagementOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Dashboard';

    protected ?string $description = 'Pantau penjualan, pembayaran, stok, dan pelanggan dalam satu tempat.';

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
            Stat::make('Penerimaan bulan ini', Product::formatCurrency((float) Order::query()->where('payment_status', 'paid')->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount')))
                ->description('Pembayaran pesanan yang sudah diterima')->color('success')->icon(Heroicon::OutlinedBanknotes)->url(Finance::getUrl()),
            Stat::make('Pesanan perlu diproses', Order::query()->whereIn('status', ['pending', 'processing'])->count())
                ->description('Pesanan baru dan sedang diproses')->color('warning')->icon(Heroicon::OutlinedShoppingBag)->url(OrderResource::getUrl('index')),
            Stat::make('Stok menipis', Product::query()->where('is_active', true)->where('is_catalog_only', false)->where('stock_quantity', '<=', 5)->count())
                ->description('Produk aktif dengan stok 5 atau kurang')->color('danger')->icon(Heroicon::OutlinedCube)
                ->url(ProductResource::getUrl('index')),
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
