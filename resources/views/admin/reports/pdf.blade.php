<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Seafood PDF</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .print-hidden {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-white p-8 text-slate-900">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black">Laporan Seafood</h1>
                <p class="mt-2 text-sm text-slate-500">Periode: {{ $filterLabel }}</p>
            </div>
            <button onclick="window.print()" class="print-hidden rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Print / Save PDF</button>
        </div>

        <div class="grid gap-4 md:grid-cols-5">
            <div class="rounded-3xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Total Penjualan</p><p class="mt-2 text-xl font-black">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p></div>
            <div class="rounded-3xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Penjualan Lunas</p><p class="mt-2 text-xl font-black">Rp {{ number_format($summary['paid_sales'], 0, ',', '.') }}</p></div>
            <div class="rounded-3xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Total Pesanan</p><p class="mt-2 text-xl font-black">{{ $summary['total_orders'] }}</p></div>
            <div class="rounded-3xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Total Menu</p><p class="mt-2 text-xl font-black">{{ $summary['total_menu'] }}</p></div>
            <div class="rounded-3xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Pelanggan</p><p class="mt-2 text-xl font-black">{{ $summary['total_customers'] }}</p></div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <div>
                <h2 class="mb-4 text-lg font-bold">Ringkasan Status Pesanan</h2>
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500"><tr><th class="border-b border-slate-200 px-3 py-2">Status</th><th class="border-b border-slate-200 px-3 py-2">Jumlah</th></tr></thead>
                    <tbody>
                        @forelse($orderStatusSummary as $row)
                            <tr><td class="border-b border-slate-100 px-3 py-2">{{ $row->label }}</td><td class="border-b border-slate-100 px-3 py-2">{{ $row->total }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="border-b border-slate-100 px-3 py-3 text-center text-slate-400">Belum ada data pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                <h2 class="mb-4 text-lg font-bold">Menu Terlaris</h2>
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500"><tr><th class="border-b border-slate-200 px-3 py-2">Menu</th><th class="border-b border-slate-200 px-3 py-2">Qty</th><th class="border-b border-slate-200 px-3 py-2">Penjualan</th></tr></thead>
                    <tbody>
                        @forelse($topItems as $item)
                            <tr><td class="border-b border-slate-100 px-3 py-2">{{ $item->item_name }}</td><td class="border-b border-slate-100 px-3 py-2">{{ $item->total_quantity }}</td><td class="border-b border-slate-100 px-3 py-2">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="border-b border-slate-100 px-3 py-3 text-center text-slate-400">Belum ada data item terjual.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="mb-4 text-lg font-bold">Detail Transaksi</h2>
            <table class="min-w-full text-sm">
                <thead class="text-left text-slate-500">
                    <tr>
                        <th class="border-b border-slate-200 px-3 py-2">No. Pesanan</th>
                        <th class="border-b border-slate-200 px-3 py-2">Tanggal</th>
                        <th class="border-b border-slate-200 px-3 py-2">Pelanggan</th>
                        <th class="border-b border-slate-200 px-3 py-2">Item</th>
                        <th class="border-b border-slate-200 px-3 py-2">Qty</th>
                        <th class="border-b border-slate-200 px-3 py-2">Status</th>
                        <th class="border-b border-slate-200 px-3 py-2">Pembayaran</th>
                        <th class="border-b border-slate-200 px-3 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailedOrders as $order)
                        <tr>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['order_number'] }}</td>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['date'] }}</td>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['customer_name'] }}<br><span class="text-xs text-slate-400">{{ $order['customer_phone'] }}</span></td>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['items_summary'] ?: '-' }}</td>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['total_quantity'] }}</td>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['status'] }}</td>
                            <td class="border-b border-slate-100 px-3 py-2">{{ $order['payment_status'] }}</td>
                            <td class="border-b border-slate-100 px-3 py-2 text-right">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="border-b border-slate-100 px-3 py-3 text-center text-slate-400">Belum ada data transaksi untuk periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($autoPrint ?? false)
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
</body>
</html>
