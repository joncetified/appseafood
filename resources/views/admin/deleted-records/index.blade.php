@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold">Deleted Records</h2>
            <p class="text-sm text-slate-500">Super Admin bisa melihat siapa yang membuat, mengubah, menghapus, dan me-restore data terhapus.</p>
        </div>
        <form method="GET" action="{{ route('admin.deleted-records.index') }}" class="flex gap-3">
            <select name="type" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                <option value="">Semua Tipe</option>
                @foreach($resources as $type => $resource)
                    <option value="{{ $type }}" @selected($selectedType === $type)>{{ $resource['label'] }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Filter</button>
        </form>
    </div>

    <div class="space-y-6">
        @foreach($resources as $type => $resource)
            @continue($selectedType !== '' && $selectedType !== $type)

            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-bold">{{ $resource['label'] }}</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Data</th>
                            <th class="px-6 py-4">Created By</th>
                            <th class="px-6 py-4">Updated By</th>
                            <th class="px-6 py-4">Deleted By</th>
                            <th class="px-6 py-4">Deleted At</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resource['records'] as $record)
                            <tr class="border-t border-slate-100">
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $record->auditLabel() }}</td>
                                <td class="px-6 py-4">{{ $record->creator?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $record->updater?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $record->deleter?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ optional($record->deleted_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.deleted-records.restore', [$type, $record->id]) }}">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data terhapus.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection
