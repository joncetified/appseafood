@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Pesanan</h2>
        <p class="text-sm text-slate-500">Dipakai oleh super admin, admin, dan kasir.</p>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr><th class="px-6 py-4">No</th><th class="px-6 py-4">Pelanggan</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Pembayaran</th><th class="px-6 py-4">Total</th><th class="px-6 py-4"></th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4">{{ $order->status }}</td>
                        <td class="px-6 py-4">{{ $order->payment_status }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-cyan-700">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
