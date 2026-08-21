<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Admin Cabe Bawang</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('admin.partials.navbar')

    <main class="container content">
        <div class="page-header">
            <h1>Laporan Pendapatan</h1>
            <div class="period-switch">
                <a href="{{ route('admin.revenue.index', ['period' => 'day']) }}" class="button button-small {{ $period === 'day' ? 'button-active' : '' }}">Per Hari</a>
                <a href="{{ route('admin.revenue.index', ['period' => 'month']) }}" class="button button-small {{ $period === 'month' ? 'button-active' : '' }}">Per Bulan</a>
                <a href="{{ route('admin.revenue.pdf', array_merge(['period' => $period], request()->only(['start_date', 'end_date', 'start_month', 'end_month']))) }}" class="button button-small button-secondary" target="_blank">🖨️ Cetak/PDF</a>
            </div>
        </div>

        <div class="filter-summary" style="margin-bottom: 1rem;">
            <strong>Filter:</strong> {{ $filterSummary ?? 'Semua periode' }}
        </div>

        <div class="revenue-filters" style="margin-bottom: 1rem;">
            <form action="{{ route('admin.revenue.index', ['period' => $period]) }}" method="GET" class="filter-form" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end;">
                @if($period === 'day')
                    <label style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.95rem;">
                        <span>Tanggal Awal</span>
                        <input type="date" name="start_date" value="{{ old('start_date', request('start_date')) }}">
                    </label>
                    <label style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.95rem;">
                        <span>Tanggal Akhir</span>
                        <input type="date" name="end_date" value="{{ old('end_date', request('end_date')) }}">
                    </label>
                @else
                    <label style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.95rem;">
                        <span>Bulan Awal</span>
                        <input type="month" name="start_month" value="{{ old('start_month', request('start_month')) }}">
                    </label>
                    <label style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.95rem;">
                        <span>Bulan Akhir</span>
                        <input type="month" name="end_month" value="{{ old('end_month', request('end_month')) }}">
                    </label>
                @endif
                <button type="submit" class="button button-small button-secondary">Terapkan Filter</button>
            </form>
        </div>

        <div class="revenue-summary">
            <div class="order-stat">
                <span class="order-stat-label">Total Pendapatan</span>
                <span class="order-stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="order-stat order-stat-completed">
                <span class="order-stat-label">Jumlah Pesanan Selesai</span>
                <span class="order-stat-value">{{ $totalOrders }}</span>
            </div>
        </div>

        <div class="revenue-list">
            @forelse($report as $item)
                <div class="revenue-row">
                    <div>
                        <strong>{{ $item['label'] }}</strong>
                        <div class="muted">{{ $item['order_count'] }} pesanan</div>
                    </div>
                    <div class="price">Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}</div>
                </div>
            @empty
                <div class="empty-state">
                    <p>Belum ada pendapatan yang tercatat.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
