@extends('layouts.admin')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold">Detail Pesanan {{ $order->order_number }}</h2>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500"><tr><th class="px-4 py-3">Item</th><th class="px-4 py-3">Qty</th><th class="px-4 py-3">Harga</th><th class="px-4 py-3">Total</th></tr></thead>
                    <tbody>
                        @forelse($order->items as $item)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">{{ $item->item_name }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada item pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-5 rounded-3xl bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-semibold">Nama Pelanggan</label>
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm">{{ $order->customer_name }}</div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Status</label>
                <select name="status" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    @foreach(['pending', 'diproses', 'siap', 'selesai', 'dibatalkan'] as $status)
                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Status Pembayaran</label>
                <select name="payment_status" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    @foreach(['unpaid', 'paid', 'refunded'] as $status)
                        <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Catatan</label>
                <textarea name="notes" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes', $order->notes) }}</textarea>
            </div>
            @if(auth()->user()?->isSuperAdmin())
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <p><span class="font-semibold">Created By:</span> {{ $order->creator?->name ?? '-' }}</p>
                    <p class="mt-1"><span class="font-semibold">Updated By:</span> {{ $order->updater?->name ?? '-' }}</p>
                </div>
            @endif
            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Update Pesanan</button>
        </form>
    </div>
@endsection
