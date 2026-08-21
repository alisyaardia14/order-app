<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <style>
        body { font-family: Arial, sans-serif; color: #3d2b22; }
        h1 { color: #7f1d1d; }
        .summary { margin: 20px 0; padding: 12px; background: #fff8ef; border: 1px solid #fdeada; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #b91c1c; color: white; }
    </style>
</head>
<body>
    <h1>Laporan Pendapatan</h1>
    <p>Periode: {{ $period === 'month' ? 'Per Bulan' : 'Per Hari' }}</p>
    @if(!empty($filterSummary) && $filterSummary !== 'Semua periode')
        <p>Filter: {{ $filterSummary }}</p>
    @endif
    <div class="summary">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}<br>
        <strong>Jumlah Pesanan Selesai:</strong> {{ $totalOrders }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Jumlah Pesanan</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
                <tr>
                    <td>{{ $item['label'] }}</td>
                    <td>{{ $item['order_count'] }}</td>
                    <td>Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
