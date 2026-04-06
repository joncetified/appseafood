@extends('layouts.admin')

@section('content')
    <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 grid gap-4 rounded-3xl bg-white p-6 shadow-sm md:grid-cols-7">
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
        <div>
            <label class="mb-2 block text-sm font-semibold">Report Mode</label>
            <select name="group_by" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                <option value="daily" @selected($filters['group_by'] === 'daily')>Daily</option>
                <option value="weekly" @selected($filters['group_by'] === 'weekly')>Weekly</option>
                <option value="monthly" @selected($filters['group_by'] === 'monthly')>Monthly</option>
                <option value="yearly" @selected($filters['group_by'] === 'yearly')>Yearly</option>
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Diagram Type</label>
            <select name="chart_type" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                <option value="bar" @selected($filters['chart_type'] === 'bar')>Chart</option>
                <option value="pie" @selected($filters['chart_type'] === 'pie')>Pie</option>
            </select>
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
            <a href="{{ route('admin.reports.print', request()->query()) }}" target="_blank" class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">Print</a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-5">
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Total Penjualan</p><p class="mt-2 text-2xl font-black">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Penjualan Lunas</p><p class="mt-2 text-2xl font-black">Rp {{ number_format($summary['paid_sales'], 0, ',', '.') }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Total Pesanan</p><p class="mt-2 text-2xl font-black">{{ $summary['total_orders'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Total Menu</p><p class="mt-2 text-2xl font-black">{{ $summary['total_menu'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Pelanggan</p><p class="mt-2 text-2xl font-black">{{ $summary['total_customers'] }}</p></div>
    </div>

    <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold">Diagram Laporan</h2>
                <p class="text-sm text-slate-500">Mode: {{ str($chart['group_by'])->title() }} | Tipe: {{ $chart['type'] === 'pie' ? 'Pie Diagram' : 'Chart Diagram' }} | Window: {{ $chart['window_label'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 px-4 py-3 text-xs text-slate-500">
                Nilai diagram memakai total income per periode.
            </div>
        </div>

        <div id="report-chart" class="mt-6 min-h-[360px] rounded-3xl border border-slate-200 bg-slate-50 p-4"></div>
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
                                <td class="px-4 py-3">{{ $row->label }}</td>
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

    <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold">Detail Transaksi</h2>
            <p class="text-sm text-slate-500">{{ $detailedOrders->count() }} pesanan</p>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">No. Pesanan</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Pembayaran</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailedOrders as $order)
                        <tr class="border-t border-slate-100 align-top">
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $order['order_number'] }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $order['date'] }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-700">{{ $order['customer_name'] }}</div>
                                <div class="text-xs text-slate-400">{{ $order['customer_phone'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $order['items_summary'] ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $order['total_quantity'] }}</td>
                            <td class="px-4 py-3">{{ $order['status'] }}</td>
                            <td class="px-4 py-3">{{ $order['payment_status'] }}</td>
                            <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-400">Belum ada data transaksi untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (() => {
            const target = document.getElementById('report-chart');
            const chart = @json($chart);
            const labels = chart.labels ?? [];
            const values = chart.sales ?? [];

            if (!target) {
                return;
            }

            if (!labels.length) {
                target.innerHTML = '<div class="flex h-full min-h-[320px] items-center justify-center text-sm text-slate-400">Belum ada data untuk diagram.</div>';
                return;
            }

            const colors = ['#0f172a', '#0f766e', '#0284c7', '#f97316', '#a16207', '#be123c', '#4338ca', '#475569', '#166534', '#7c3aed'];

            if (chart.type === 'pie') {
                const total = values.reduce((sum, value) => sum + value, 0);
                let currentAngle = 0;

                const slices = values.map((value, index) => {
                    const fraction = total === 0 ? 0 : value / total;
                    const angle = fraction * Math.PI * 2;
                    const x1 = 160 + Math.cos(currentAngle) * 120;
                    const y1 = 160 + Math.sin(currentAngle) * 120;
                    const x2 = 160 + Math.cos(currentAngle + angle) * 120;
                    const y2 = 160 + Math.sin(currentAngle + angle) * 120;
                    const largeArc = angle > Math.PI ? 1 : 0;
                    const path = `M 160 160 L ${x1} ${y1} A 120 120 0 ${largeArc} 1 ${x2} ${y2} Z`;
                    const fill = colors[index % colors.length];
                    currentAngle += angle;

                    return `<path d="${path}" fill="${fill}"></path>`;
                }).join('');

                const legend = labels.map((label, index) => {
                    const value = new Intl.NumberFormat('id-ID').format(values[index] ?? 0);
                    return `<div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3"><div class="flex items-center gap-3"><span class="h-3 w-3 rounded-full" style="background:${colors[index % colors.length]}"></span><span class="text-sm font-medium text-slate-700">${label}</span></div><span class="text-sm text-slate-500">Rp ${value}</span></div>`;
                }).join('');

                target.innerHTML = `
                    <div class="grid gap-6 lg:grid-cols-[360px,1fr]">
                        <svg viewBox="0 0 320 320" class="mx-auto w-full max-w-[320px]">${slices}</svg>
                        <div class="grid gap-3">${legend}</div>
                    </div>
                `;

                return;
            }

            const max = Math.max(...values, 1);
            const bars = labels.map((label, index) => {
                const value = values[index] ?? 0;
                const height = Math.max(24, (value / max) * 220);
                const fill = colors[index % colors.length];
                const x = 40 + (index * 90);
                const y = 260 - height;

                return `
                    <g>
                        <rect x="${x}" y="${y}" width="48" height="${height}" rx="16" fill="${fill}"></rect>
                        <text x="${x + 24}" y="286" text-anchor="middle" font-size="11" fill="#475569">${label}</text>
                        <text x="${x + 24}" y="${y - 8}" text-anchor="middle" font-size="11" fill="#0f172a">${new Intl.NumberFormat('id-ID').format(value)}</text>
                    </g>
                `;
            }).join('');

            const width = Math.max(720, labels.length * 90 + 80);

            target.innerHTML = `
                <div class="overflow-x-auto">
                    <svg viewBox="0 0 ${width} 300" class="min-w-[720px] w-full">
                        <line x1="30" y1="260" x2="${width - 20}" y2="260" stroke="#cbd5e1" stroke-width="2"></line>
                        ${bars}
                    </svg>
                </div>
            `;
        })();
    </script>
@endsection
