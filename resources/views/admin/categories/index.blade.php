@extends('layouts.admin')

@section('content')
    @php($isSuperAdmin = auth()->user()?->isSuperAdmin())
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold">Kategori</h2>
            <p class="text-sm text-slate-500">Kelola kategori seafood.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Tambah Kategori</a>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Status</th>
                    @if($isSuperAdmin)
                        <th class="px-6 py-4">Created By</th>
                        <th class="px-6 py-4">Updated By</th>
                    @endif
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">{{ $category->name }}</td>
                        <td class="px-6 py-4">{{ $category->slug }}</td>
                        <td class="px-6 py-4">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        @if($isSuperAdmin)
                            <td class="px-6 py-4">{{ $category->creator?->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $category->updater?->name ?? '-' }}</td>
                        @endif
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="mr-3 font-medium text-cyan-700">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="font-medium text-rose-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $isSuperAdmin ? 6 : 4 }}" class="px-6 py-8 text-center text-slate-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $categories->links() }}</div>
@endsection
