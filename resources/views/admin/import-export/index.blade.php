@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Import / Export</h2>
        <p class="text-sm text-slate-500">Admin dan Super Admin bisa export, import, dan backup data users serta items.</p>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold">Users</h3>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.import-export.users.export') }}" class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm font-semibold text-cyan-700">Export CSV Users</a>
                <form method="POST" action="{{ route('admin.import-export.users.backup') }}">
                    @csrf
                    <button type="submit" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">Backup Users</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.import-export.users.import') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Import CSV Users</label>
                    <input type="file" name="users_file" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                </div>
                <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Import Users</button>
            </form>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold">Items</h3>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.import-export.items.export') }}" class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm font-semibold text-cyan-700">Export CSV Items</a>
                <form method="POST" action="{{ route('admin.import-export.items.backup') }}">
                    @csrf
                    <button type="submit" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">Backup Items</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.import-export.items.import') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Import CSV Items</label>
                    <input type="file" name="items_file" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                </div>
                <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Import Items</button>
            </form>
        </div>
    </div>
@endsection
