<div class="account-card__title">Riwayat Order</div>
<p class="account-card__hint">Lihat status pesanan dan buka detail order yang sudah pernah kamu checkout.</p>

@if ($accountOrders->isEmpty())
    <div class="account-empty">
        Belum ada order. Setelah checkout berhasil, riwayat pesanan akan muncul di sini.
    </div>
@else
    <div class="table-content table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accountOrders as $order)
                    @php
                        $statusClass = match ($order->status?->value) {
                            'processing' => 'account-order-status--processing',
                            'completed' => 'account-order-status--completed',
                            'cancelled' => 'account-order-status--cancelled',
                            default => 'account-order-status--pending',
                        };
                    @endphp
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->checked_out_at?->format('d M Y H:i') ?: '-' }}</td>
                        <td><span class="account-order-status {{ $statusClass }}">{{ $order->status->label() }}</span></td>
                        <td>{{ $order->total_label }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="account-order-link">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($accountOrders->hasPages())
        <div class="account-orders-pagination">
            <div class="account-orders-pagination__summary">
                Halaman {{ $accountOrders->currentPage() }} dari {{ $accountOrders->lastPage() }}
            </div>
            <div class="account-orders-pagination__links">
                @if ($accountOrders->onFirstPage())
                    <span class="account-orders-pagination__link is-disabled">Prev</span>
                @else
                    <a class="account-orders-pagination__link" href="{{ $accountOrders->previousPageUrl() }}">Prev</a>
                @endif

                @if ($accountOrders->hasMorePages())
                    <a class="account-orders-pagination__link" href="{{ $accountOrders->nextPageUrl() }}">Next</a>
                @else
                    <span class="account-orders-pagination__link is-disabled">Next</span>
                @endif
            </div>
        </div>
    @endif
@endif
