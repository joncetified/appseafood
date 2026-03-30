<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Seafood</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #0f172a;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 18px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #e2e8f0;
            text-align: left;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 18px 0 8px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="title">Laporan Seafood</div>
    <div class="subtitle">Periode: {{ $filterLabel }}</div>

    <div class="section-title">Ringkasan</div>
    <table>
        <tbody>
            <tr><th>Total Penjualan</th><td>Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</td></tr>
            <tr><th>Penjualan Lunas</th><td>Rp {{ number_format($summary['paid_sales'], 0, ',', '.') }}</td></tr>
            <tr><th>Total Pesanan</th><td>{{ $summary['total_orders'] }}</td></tr>
            <tr><th>Total Menu</th><td>{{ $summary['total_menu'] }}</td></tr>
            <tr><th>Total Pelanggan</th><td>{{ $summary['total_customers'] }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">Ringkasan Status Pesanan</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orderStatusSummary as $row)
                <tr>
                    <td>{{ $row->label }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Belum ada data pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Menu Terlaris</div>
    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topItems as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->total_quantity }}</td>
                    <td>Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Belum ada data item terjual.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Detail Transaksi</div>
    <table>
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>No. Telepon</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailedOrders as $order)
                <tr>
                    <td>{{ $order['order_number'] }}</td>
                    <td>{{ $order['date'] }}</td>
                    <td>{{ $order['customer_name'] }}</td>
                    <td>{{ $order['customer_phone'] }}</td>
                    <td>{{ $order['items_summary'] ?: '-' }}</td>
                    <td>{{ $order['total_quantity'] }}</td>
                    <td>{{ $order['status'] }}</td>
                    <td>{{ $order['payment_status'] }}</td>
                    <td class="text-right">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada data transaksi untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
