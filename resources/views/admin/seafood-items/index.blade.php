@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div><h2 class="text-xl font-bold">Menu Seafood</h2><p class="text-sm text-slate-500">Kelola semua menu seafood.</p></div>
        <a href="{{ route('admin.seafood-items.create') }}" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Tambah Menu</a>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr><th class="px-6 py-4">Nama</th><th class="px-6 py-4">Kategori</th><th class="px-6 py-4">Harga</th><th class="px-6 py-4">Status</th><th class="px-6 py-4"></th></tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">{{ $item->name }}</td>
                        <td class="px-6 py-4">{{ $item->category?->name ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $item->is_available ? 'Tersedia' : 'Tidak tersedia' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.seafood-items.edit', $item) }}" class="mr-3 font-medium text-cyan-700">Edit</a>
                            <form method="POST" action="{{ route('admin.seafood-items.destroy', $item) }}" class="inline">@csrf @method('DELETE')<button type="submit" class="font-medium text-rose-600">Hapus</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada menu seafood.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $items->links() }}</div>
@endsection
