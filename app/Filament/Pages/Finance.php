<?php

namespace App\Filament\Pages;

use App\Support\FinanceReport;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class Finance extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $title = 'Laporan Keuangan';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.finance';

    public string $month = '';

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function report(): array
    {
        $this->validate(['month' => ['required', 'date_format:Y-m']]);

        return app(FinanceReport::class)->forMonth($this->month);
    }

    public function export(): StreamedResponse
    {
        $report = $this->report();

        return response()->streamDownload(function () use ($report): void {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['Tanggal', 'Referensi', 'Keterangan', 'Jenis', 'Nominal (IDR)'], ';', '"', '');
            foreach ($report['entries'] as $entry) {
                $row = array_map(fn ($value) => is_string($value) && preg_match('/^[=+@\-\t\r]/', $value) ? "'".$value : $value, array_values($entry));
                fputcsv($stream, $row, ';', '"', '');
            }
            fclose($stream);
        }, 'laporan-keuangan-'.$this->month.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
