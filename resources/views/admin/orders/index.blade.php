<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $paymentPage ?? false ? 'Menyelesaikan Pembayaran' : 'Kelola Pesanan' }} - Admin Cabe Bawang</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('admin.partials.navbar')

    <main class="container content">
        <div class="page-header">
            <h1>{{ $paymentPage ?? false ? 'Menyelesaikan Pembayaran' : 'Kelola Pesanan' }}</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="order-stats">
            <div class="order-stat">
                <span class="order-stat-value">{{ $orders->count() }}</span>
                <span class="order-stat-label">Total Pesanan</span>
            </div>
            <div class="order-stat order-stat-ready">
                <span class="order-stat-value">{{ $statusCounts['ready'] ?? 0 }}</span>
                <span class="order-stat-label">Belum Dibayar</span>
            </div>
            <div class="order-stat order-stat-completed">
                <span class="order-stat-value">{{ $statusCounts['completed'] ?? 0 }}</span>
                <span class="order-stat-label">Sudah Dibayar</span>
            </div>
        </div>

        <div class="order-list">
            @forelse($orders as $order)
                @if($paymentPage ?? false)
                    @continue($order->status !== 'ready')
                @endif

                <article class="order-row order-row-{{ $order->status }}">
                    <div class="order-row-top">
                        <div class="order-row-top-left">
                            <span class="order-row-code">Meja {{ $order->table_number ?? '-' }}</span>
                            <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                        </div>
                        <span class="muted">🕒 {{ $order->created_at->format('d-m-Y H:i') }}</span>
                    </div>

                    <div class="order-row-body">
                        <div class="order-row-col">
                            <span class="order-row-label">Nomor Meja</span>
                            <strong>{{ $order->table_number ?? '-' }}</strong>
                        </div>

                        <div class="order-row-col">
                            <span class="order-row-label">Pemesan</span>
                            <strong>{{ $order->customer_name }}</strong>
                            <span class="muted">📞 {{ $order->customer_phone }}</span>
                        </div>

                        <div class="order-row-col">
                            <span class="order-row-label">Item</span>
                            @foreach($order->items as $item)
                                <div class="order-row-item">{{ $item->menu_name }} <span class="muted">× {{ $item->quantity }}</span></div>
                            @endforeach
                        </div>

                        <div class="order-row-col">
                            <span class="order-row-label">Catatan</span>
                            @if($order->notes)
                                <p class="order-row-notes">📝 {{ $order->notes }}</p>
                            @else
                                <span class="muted">Tidak ada catatan.</span>
                            @endif
                        </div>

                        <div class="order-row-col order-row-total">
                            <span class="order-row-label">Total</span>
                            <strong class="price">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    @if($paymentPage ?? false)
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="status-form order-row-footer">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="source" value="payment">
                            <div class="payment-status {{ $order->status === 'completed' ? 'payment-status-paid' : 'payment-status-unpaid' }}">
                                {{ $order->status === 'completed' ? 'Sudah Bayar' : 'Belum Dibayar' }}
                            </div>
                            @if($order->status === 'completed')
                                <button type="button" class="button button-small payment-button payment-button-paid" disabled>Sudah Bayar</button>
                            @else
                                <button type="submit" class="button button-small payment-button payment-button-unpaid">Belum Dibayar</button>
                            @endif
                        </form>
                    @endif
                </article>
            @empty
                <div class="empty-state">
                    <p>Belum ada pesanan.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
