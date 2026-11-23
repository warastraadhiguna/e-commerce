<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }} · {{ $companyProfile->company_name }}</title>
    <style>
        *{box-sizing:border-box}body{font:14px/1.6 Arial,sans-serif;color:#242424;background:#f4f5f7;margin:0;padding:40px 20px}.invoice{max-width:900px;margin:auto;background:white;padding:48px;border-top:6px solid var(--brand-primary, #fed700)}.header,.columns{display:flex;justify-content:space-between;gap:32px}.brand{font-size:28px;font-weight:800;letter-spacing:1px}.muted{color:#6b7280}.badge{display:inline-block;padding:5px 14px;border-radius:20px;background:{{ $order->payment_status === 'paid' ? '#dcfce7' : '#fef3c7' }}}h1{font-size:30px;margin:0}.columns{margin:35px 0}.columns>div{flex:1}table{width:100%;border-collapse:collapse}th,td{padding:14px 10px;border-bottom:1px solid #e5e7eb;text-align:left}th{background:#f8fafc}.right{text-align:right}.total{font-size:22px;font-weight:bold}.actions{max-width:900px;margin:0 auto 20px;display:flex;gap:16px;align-items:center}.button{border:0;background:var(--brand-primary, #fed700);padding:12px 24px;font-weight:bold;cursor:pointer;color:#242424;text-decoration:none}.footer{border-top:1px solid #e5e7eb;margin-top:36px;padding-top:20px;font-size:12px}@media(max-width:600px){.invoice{padding:24px 16px}.header,.columns{flex-direction:column;gap:16px}table{font-size:12px}th,td{padding:8px 4px}}@media print{body{background:white;padding:0}.invoice{max-width:none;padding:20px}.actions{display:none}tr{break-inside:avoid}@page{size:A4;margin:15mm}}
    </style>
    @include('partials.brand-theme')
</head>
<body class="invoice-page">
    <div class="actions"><button class="button" onclick="window.print()">Cetak / Simpan PDF</button><a href="{{ auth()->user()->isManagementUser() ? \App\Filament\Resources\Orders\OrderResource::getUrl('edit', ['record' => $order]) : route('orders.show', $order) }}">Kembali ke pesanan</a></div>
    <main class="invoice">
        <div class="header"><div><div class="brand">@if ($companyProfile->has_logo)<img class="invoice-logo" src="{{ $companyProfile->logo_url }}" alt="{{ $companyProfile->company_name }}">@else{{ $companyProfile->company_name }}@endif</div><div class="muted">{{ $companyProfile->address }}<br>{{ $companyProfile->email }} · {{ $companyProfile->phone }}</div></div><div><h1>INVOICE</h1><div>{{ $order->order_number }}</div><div class="muted">{{ $order->checked_out_at?->format('d M Y H:i') }}</div><span class="badge">{{ $order->payment_status_label }}</span></div></div>
        <div class="columns"><div><strong>Ditagihkan kepada</strong><div>{{ $order->customer_name }}</div><div class="muted">{{ $order->customer_email }}<br>{{ $order->customer_phone }}</div></div><div><strong>Dikirim kepada</strong><div>{{ $order->shipping_recipient_name }}</div><div class="muted">{{ $order->shipping_full_address }}<br>{{ $order->shipping_phone }}</div></div></div>
        <table><thead><tr><th>Produk</th><th class="right">Jumlah</th><th class="right">Harga satuan</th><th class="right">Subtotal</th></tr></thead><tbody>
            @foreach ($order->items as $item)<tr><td><strong>{{ $item->product_name }}</strong><br><span class="muted">{{ $item->product_sku }}</span></td><td class="right">{{ $item->quantity_label }}</td><td class="right">{{ $item->unit_price_label }}</td><td class="right">{{ $item->line_total_label }}</td></tr>@endforeach
            <tr><td colspan="3" class="right">Subtotal</td><td class="right">{{ $order->subtotal_label }}</td></tr><tr><td colspan="3" class="right total">Total</td><td class="right total">{{ $order->total_label }}</td></tr>
        </tbody></table>
        @if ($order->payment_status === 'paid')<p><strong>Pembayaran diterima:</strong> {{ $order->paid_at?->format('d M Y H:i') }} · {{ ['transfer' => 'Transfer bank', 'cash' => 'Tunai', 'qris' => 'QRIS'][$order->payment_method] ?? 'Terverifikasi' }} @if($order->payment_reference) · {{ $order->payment_reference }} @endif</p>@else<p class="muted">Pembayaran belum dikonfirmasi. Hubungi admin toko untuk petunjuk dan konfirmasi pembayaran.</p>@endif
        @if ($order->user_notes)<p><strong>Catatan:</strong> {{ $order->user_notes }}</p>@endif
        <div class="footer muted">Terima kasih telah berbelanja di {{ $companyProfile->company_name }}. Dokumen ini dibuat dari data pesanan. Status pesanan: {{ $order->status->label() }}.</div>
    </main>
</body>
</html>
