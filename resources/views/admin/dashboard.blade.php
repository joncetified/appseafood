@extends('layouts.admin')

@section('content')
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $order->order_number }}</td>
                            <td class="px-4 py-3">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3">{{ $order->status }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
