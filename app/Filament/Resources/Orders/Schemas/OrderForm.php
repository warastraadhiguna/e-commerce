<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan order')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')
                            ->label('No. Order')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('status_display')
                            ->label('Status saat ini')
                            ->formatStateUsing(fn (?Order $record): string => $record?->status?->label() ?? '-')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('customer_name')
                            ->label('Customer')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('customer_role')
                            ->label('Role customer')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('customer_phone')
                            ->label('Nomor HP')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('checked_out_at')
                            ->label('Tanggal checkout')
                            ->formatStateUsing(fn (?Order $record): string => $record?->checked_out_at?->format('d M Y H:i') ?? '-')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('total_quantity')
                            ->label('Total qty')
                            ->formatStateUsing(fn (?Order $record): string => $record?->total_quantity_label ?? '-')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('total_amount')
                            ->label('Total belanja')
                            ->formatStateUsing(fn (?Order $record): string => $record?->total_label ?? '-')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make('Pengiriman')
                    ->columns(2)
                    ->schema([
                        TextInput::make('shipping_label')
                            ->label('Label alamat')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('shipping_recipient_name')
                            ->label('Penerima')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('shipping_phone')
                            ->label('Nomor HP tujuan')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('uses_partner_pricing_display')
                            ->label('Tipe harga')
                            ->formatStateUsing(fn (?Order $record): string => $record?->uses_partner_pricing ? 'Harga mitra' : 'Harga buyer')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('shipping_address_full')
                            ->label('Alamat pengiriman')
                            ->formatStateUsing(fn (?Order $record): string => $record?->shipping_full_address ?? '-')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Textarea::make('shipping_notes')
                            ->label('Catatan alamat')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Item pesanan')
                    ->schema([
                        Textarea::make('items_summary')
                            ->label('Ringkasan item')
                            ->formatStateUsing(function (?Order $record): string {
                                if (! $record) {
                                    return '-';
                                }

                                return $record->items
                                    ->map(fn ($item): string => "{$item->product_name} | {$item->quantity_label} | {$item->unit_price_label} | {$item->line_total_label}")
                                    ->implode(PHP_EOL);
                            })
                            ->rows(8)
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make('Tindak lanjut')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status order')
                            ->options(OrderStatus::options())
                            ->required()
                            ->native(false),
                        Textarea::make('internal_notes')
                            ->label('Catatan internal')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Pembayaran')
                    ->description('Tandai lunas setelah pembayaran benar-benar diterima. Pesanan lunas otomatis masuk laporan keuangan.')
                    ->columns(2)
                    ->schema([
                        Select::make('payment_status')->label('Status pembayaran')
                            ->options(['unpaid' => 'Belum dibayar', 'paid' => 'Lunas'])->required()->live()->native(false),
                        Select::make('payment_method')->label('Metode pembayaran')
                            ->options(['transfer' => 'Transfer bank', 'cash' => 'Tunai', 'qris' => 'QRIS'])
                            ->required(fn ($get): bool => $get('payment_status') === 'paid')->native(false),
                        TextInput::make('payment_reference')->label('Referensi pembayaran')->maxLength(255),
                        TextInput::make('paid_at_display')->label('Diterima pada')
                            ->formatStateUsing(fn (?Order $record): string => $record?->paid_at?->format('d M Y H:i') ?? 'Dicatat otomatis saat ditandai lunas')
                            ->disabled()->dehydrated(false),
                    ]),
            ]);
    }
}
