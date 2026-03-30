@extends('layouts.admin')

@section('content')
    <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 grid gap-4 rounded-3xl bg-white p-6 shadow-sm md:grid-cols-5">
        <div>
            <label class="mb-2 block text-sm font-semibold">Filter Bulan</label>
            <input name="month" type="month" value="{{ $filters['month'] }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Tanggal Mulai</label>
            <input name="start_date" type="date" value="{{ $filters['start_date'] }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Tanggal Selesai</label>
            <input name="end_date" type="date" value="{{ $filters['end_date'] }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Terapkan Filter</button>
        </div>
        <div class="flex items-end">
            <a href="{{ route('admin.reports.index') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700">Reset</a>
        </div>
    </form>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold">Laporan Seafood</h2>
            <p class="text-sm text-slate-500">Periode: {{ $filterLabel }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reports.export.excel', request()->query()) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">Export Excel</a>
            <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" target="_blank" class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm font-semibold text-cyan-700">Export PDF</a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-5">
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Total Penjualan</p><p class="mt-2 text-2xl font-black">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Penjualan Lunas</p><p class="mt-2 text-2xl font-black">Rp {{ number_format($summary['paid_sales'], 0, ',', '.') }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Total Pesanan</p><p class="mt-2 text-2xl font-black">{{ $summary['total_orders'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Total Menu</p><p class="mt-2 text-2xl font-black">{{ $summary['total_menu'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Pelanggan</p><p class="mt-2 text-2xl font-black">{{ $summary['total_customers'] }}</p></div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold">Ringkasan Status Pesanan</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500">
                        <tr><th class="px-4 py-3">Status</th><th class="px-4 py-3">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        @forelse($orderStatusSummary as $row)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">{{ $row->status }}</td>
                                <td class="px-4 py-3">{{ $row->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-slate-400">Belum ada data pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold">Menu Terlaris</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500">
                        <tr><th class="px-4 py-3">Menu</th><th class="px-4 py-3">Qty</th><th class="px-4 py-3">Penjualan</th></tr>
                    </thead>
                    <tbody>
                        @forelse($topItems as $item)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">{{ $item->item_name }}</td>
                                <td class="px-4 py-3">{{ $item->total_quantity }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-slate-400">Belum ada data item terjual.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
