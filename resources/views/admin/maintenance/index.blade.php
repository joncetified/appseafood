@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Backup & Maintenance</h2>
        <p class="text-sm text-slate-500">Menu untuk backup database dan refresh koneksi/cache Laravel saat ada masalah.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('admin.maintenance.backup-database') }}" class="rounded-3xl bg-white p-6 shadow-sm">
            @csrf
            <h3 class="text-lg font-bold">Backup Database</h3>
            <p class="mt-2 text-sm text-slate-500">Menyimpan snapshot JSON semua tabel aplikasi ke storage lokal.</p>
            <button type="submit" class="mt-6 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Buat Backup Database</button>
        </form>

        <form method="POST" action="{{ route('admin.maintenance.restart-database') }}" class="rounded-3xl bg-white p-6 shadow-sm">
            @csrf
            <h3 class="text-lg font-bold">Restart DB Connection</h3>
            <p class="mt-2 text-sm text-slate-500">Merefresh koneksi database dan menjalankan `optimize:clear` untuk Laravel.</p>
            <button type="submit" class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">Refresh Koneksi Database</button>
        </form>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="text-lg font-bold">Riwayat Backup</h3>
        </div>
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-6 py-4">Label</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Created By</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $backup->label }}</td>
                        <td class="px-6 py-4">{{ $backup->type }}</td>
                        <td class="px-6 py-4">{{ $backup->creator?->name ?? 'System' }}</td>
                        <td class="px-6 py-4">{{ $backup->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($backup->file_path)
                                <a href="{{ route('admin.maintenance.backups.download', $backup) }}" class="font-medium text-cyan-700">Download</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada backup.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $backups->links() }}</div>
@endsection
