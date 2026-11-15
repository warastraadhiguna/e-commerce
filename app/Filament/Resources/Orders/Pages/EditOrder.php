<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('invoice')->label('Cetak Invoice')->icon('heroicon-o-printer')
                ->url(fn (): string => route('orders.invoice', $this->record))->openUrlInNewTab(),
            DeleteAction::make()->hidden(),
        ];
    }
}
