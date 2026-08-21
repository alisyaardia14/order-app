<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Cetak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', Arial, sans-serif;
            color: #3d2b22;
            background: white;
            padding: 20px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #b91c1c;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #b91c1c;
            margin-bottom: 5px;
        }

        .header p {
            color: #7c6a5f;
            font-size: 14px;
        }

        .summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-box {
            background: #fff8ef;
            border: 1px solid #fdeada;
            border-left: 5px solid #b91c1c;
            padding: 15px;
            border-radius: 5px;
        }

        .summary-label {
            font-size: 12px;
            color: #7c6a5f;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 800;
            color: #b91c1c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: #b91c1c;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 700;
            font-size: 13px;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background: #fff8ef;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #7c6a5f;
        }

        .period-label {
            display: inline-block;
            background: #d97706;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .no-print {
                display: none;
            }

            .container {
                max-width: 100%;
                margin: 0;
            }

            .header {
                page-break-after: avoid;
            }
        }

        .print-button {
            background: #b91c1c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 20px;
            no-print: true;
        }

        .print-button:hover {
            background: #7f1d1d;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="print-button no-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

        <div class="header">
            <h1>Rumah Makan Cabe Bawang</h1>
            <h2 style="font-size: 16px; color: #7f1d1d; font-weight: 700; margin-bottom: 10px;">Laporan Pendapatan</h2>
            <p>Periode: {{ $period === 'month' ? 'Per Bulan' : 'Per Hari' }}</p>
            <p>Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>

        <span class="period-label">{{ $period === 'month' ? 'PER BULAN' : 'PER HARI' }}</span>

        <div class="summary">
            <div class="summary-box">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Jumlah Pesanan Selesai</div>
                <div class="summary-value">{{ $totalOrders }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Periode</th>
                    <th class="text-center">Jumlah Pesanan</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report as $item)
                    <tr>
                        <td>{{ $item['label'] }}</td>
                        <td class="text-center">{{ $item['order_count'] }}</td>
                        <td class="text-right"><strong>Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada pendapatan yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Laporan ini dicetak oleh sistem Rumah Makan Cabe Bawang pada {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
