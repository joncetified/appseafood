<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Seafood PDF</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white p-8 text-slate-900">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black">Laporan Seafood</h1>
                <p class="mt-2 text-sm text-slate-500">Periode: {{ $filterLabel }}</p>
            </div>
            <button onclick="window.print()" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white print:hidden">Print / Save PDF</button>
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
                        @foreach($orderStatusSummary as $row)
                            <tr><td class="border-b border-slate-100 px-3 py-2">{{ $row->status }}</td><td class="border-b border-slate-100 px-3 py-2">{{ $row->total }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                <h2 class="mb-4 text-lg font-bold">Menu Terlaris</h2>
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500"><tr><th class="border-b border-slate-200 px-3 py-2">Menu</th><th class="border-b border-slate-200 px-3 py-2">Qty</th><th class="border-b border-slate-200 px-3 py-2">Penjualan</th></tr></thead>
                    <tbody>
                        @foreach($topItems as $item)
                            <tr><td class="border-b border-slate-100 px-3 py-2">{{ $item->item_name }}</td><td class="border-b border-slate-100 px-3 py-2">{{ $item->total_quantity }}</td><td class="border-b border-slate-100 px-3 py-2">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
