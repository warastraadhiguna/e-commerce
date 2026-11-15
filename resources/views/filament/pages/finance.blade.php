<x-filament-panels::page>
    @php($report = $this->report())
    <style>
        .finance-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}.finance-toolbar p{color:#6b7280;font-size:14px;margin-top:6px}.finance-controls{display:flex;gap:12px;align-items:center}.finance-month{border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;background:transparent;color:inherit}.finance-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}.finance-stat,.finance-panel{border:1px solid #e5e7eb;border-radius:12px;background:white;padding:24px}.finance-stat small{color:#6b7280}.finance-value{font-size:24px;font-weight:700;margin:12px 0 5px;overflow-wrap:anywhere}.finance-stat p{font-size:12px;color:#6b7280}.finance-panel h3{font-weight:600;margin-bottom:20px}.finance-table{width:100%;text-align:left;font-size:14px;border-collapse:collapse}.finance-table td,.finance-table th{padding:14px 12px;border-bottom:1px solid #e5e7eb}.finance-table th{color:#6b7280;font-weight:500}.finance-chart{height:180px;display:flex;align-items:flex-end;gap:5px;margin-top:20px}.finance-day{display:flex;align-items:flex-end;flex:1;height:100%;gap:2px;position:relative;padding-bottom:24px}.finance-day span{position:absolute;bottom:0;font-size:10px;color:#6b7280}.finance-bar{flex:1;min-height:2px;border-radius:3px 3px 0 0}.dark .finance-stat,.dark .finance-panel{background:#18181b;border-color:#3f3f46}.dark .finance-table td,.dark .finance-table th{border-color:#3f3f46}@media(max-width:900px){.finance-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:520px){.finance-stats{grid-template-columns:1fr}.finance-panel{padding:16px}.finance-value{font-size:22px}}
    </style>
    <div class="finance-toolbar">
        <div><h2 style="font-size:20px;font-weight:600">Arus kas · {{ $report['month_label'] }}</h2><p>Penerimaan dari pesanan lunas dan pengeluaran yang sudah dicatat.</p></div>
        <div class="finance-controls"><label for="finance-month">Periode</label><input id="finance-month" class="finance-month" type="month" wire:model.live="month" required><x-filament::button wire:click="export" icon="heroicon-o-arrow-down-tray">Unduh CSV</x-filament::button></div>
    </div>
    <div class="finance-stats">
        @foreach ([['Penerimaan', $report['income'], $report['paid_count'].' pembayaran lunas', '#059669'], ['Pengeluaran', $report['expenses'], 'Biaya yang dicatat pada periode ini', '#e11d48'], ['Selisih kas', $report['balance'], 'Penerimaan dikurangi pengeluaran', '#d97706'], ['Tagihan belum lunas', $report['receivables'], 'Semua periode, kecuali pesanan batal', '#2563eb']] as [$label, $value, $hint, $color])
            <div class="finance-stat"><small>{{ $label }}</small><div class="finance-value" style="color:{{ $color }}">{{ \App\Models\Product::formatCurrency($value) }}</div><p>{{ $hint }}</p></div>
        @endforeach
    </div>
    <div class="finance-panel">
        <h3>Aktivitas kas harian</h3><p style="font-size:13px;color:#6b7280">🟢 Penerimaan &nbsp; 🔴 Pengeluaran</p>
        @php($maximum = max(1, $report['daily']->max('income'), $report['daily']->max('expense')))
        <div class="finance-chart" role="img" aria-label="Grafik penerimaan dan pengeluaran harian">
            @foreach ($report['daily'] as $day)
                <div class="finance-day" title="{{ $day['day'] }}: masuk {{ \App\Models\Product::formatCurrency($day['income']) }}, keluar {{ \App\Models\Product::formatCurrency($day['expense']) }}"><div class="finance-bar" style="background:#10b981;height:{{ max(1, $day['income'] / $maximum * 85) }}%"></div><div class="finance-bar" style="background:#f43f5e;height:{{ max(1, $day['expense'] / $maximum * 85) }}%"></div><span>{{ $day['day'] }}</span></div>
            @endforeach
        </div>
    </div>
    <div class="finance-panel">
        <div class="finance-toolbar"><h3>Rincian transaksi</h3><a href="{{ \App\Filament\Resources\Expenses\ExpenseResource::getUrl('create') }}" style="color:#d97706;font-weight:600">+ Catat pengeluaran</a></div>
        <div style="overflow-x:auto"><table class="finance-table"><thead><tr><th>Tanggal</th><th>Referensi</th><th>Keterangan</th><th>Jenis</th><th style="text-align:right">Nominal</th></tr></thead><tbody>
            @forelse ($report['entries'] as $entry)
                <tr><td style="white-space:nowrap">{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td><td>{{ $entry['reference'] }}</td><td>{{ $entry['description'] }}</td><td style="color:{{ $entry['type'] === 'Pemasukan' ? '#059669' : '#e11d48' }}">{{ $entry['type'] }}</td><td style="text-align:right;white-space:nowrap;font-weight:600">{{ \App\Models\Product::formatCurrency($entry['amount']) }}</td></tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#6b7280">Belum ada transaksi pada periode ini. Konfirmasi pembayaran pesanan atau catat pengeluaran untuk memulai.</td></tr>
            @endforelse
        </tbody></table></div>
        <p style="font-size:12px;color:#6b7280;margin-top:18px">Laporan kas sederhana. Selisih kas bukan laba bersih; HPP, pajak, dan penyusutan belum dihitung. Pembayaran diverifikasi secara manual melalui menu Pesanan.</p>
    </div>
</x-filament-panels::page>
