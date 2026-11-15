<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentOrders extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table->heading('Pesanan Terbaru')->query(Order::query())->defaultSort('checked_out_at', 'desc')
            ->paginated([5])->columns([
                TextColumn::make('order_number')->label('No. Pesanan')->searchable(),
                TextColumn::make('customer_name')->label('Pelanggan'),
                TextColumn::make('total_amount')->label('Total')->money('IDR'),
                TextColumn::make('status')->badge()->formatStateUsing(fn (OrderStatus $state): string => $state->label())->color(fn (OrderStatus $state): string => $state->color()),
                TextColumn::make('payment_status')->label('Pembayaran')->badge()->formatStateUsing(fn (string $state): string => $state === 'paid' ? 'Lunas' : 'Belum dibayar')->color(fn (string $state): string => $state === 'paid' ? 'success' : 'warning'),
                TextColumn::make('checked_out_at')->label('Tanggal')->dateTime('d M Y H:i'),
            ])->recordUrl(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record]));
    }
}
