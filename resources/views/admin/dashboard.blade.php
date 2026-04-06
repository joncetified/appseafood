@extends('layouts.admin')

@section('content')
    @if($showIncomeSummary)
        <div class="mb-8 rounded-3xl bg-slate-900 p-6 text-white shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-cyan-300">Income Report</p>
                    <h2 class="mt-3 text-2xl font-black">Daily || Monthly</h2>
                    <p class="mt-2 text-sm text-slate-300">Hanya terlihat untuk Super Admin dan Manager.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-xs text-slate-300">DAILY || TODAY</p>
                        <p class="mt-2 text-2xl font-black">Rp {{ number_format($incomeSummary['today'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-xs text-slate-300">DAILY || YESTERDAY</p>
                        <p class="mt-2 text-2xl font-black">Rp {{ number_format($incomeSummary['yesterday'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-xs text-slate-300">MONTHLY || THIS MONTH</p>
                        <p class="mt-2 text-2xl font-black">Rp {{ number_format($incomeSummary['this_month'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-xs text-slate-300">MONTHLY || LAST MONTH</p>
                        <p class="mt-2 text-2xl font-black">Rp {{ number_format($incomeSummary['last_month'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">User</p><p class="mt-2 text-3xl font-black">{{ $stats['users'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Kategori</p><p class="mt-2 text-3xl font-black">{{ $stats['categories'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Menu</p><p class="mt-2 text-3xl font-black">{{ $stats['menu_items'] }}</p></div>
        <div class="rounded-3xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Pesanan</p><p class="mt-2 text-3xl font-black">{{ $stats['orders'] }}</p></div>
    </div>

    <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold">Pesanan Terbaru</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Total</th>
                        @if(auth()->user()?->isSuperAdmin())
                            <th class="px-4 py-3">Updated By</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $order->order_number }}</td>
                            <td class="px-4 py-3">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3">{{ $order->status }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            @if(auth()->user()?->isSuperAdmin())
                                <td class="px-4 py-3">{{ $order->updater?->name ?? $order->creator?->name ?? '-' }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user()?->isSuperAdmin() ? 5 : 4 }}" class="px-4 py-6 text-center text-slate-400">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
