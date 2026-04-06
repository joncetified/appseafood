@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Access Control</h2>
        <p class="text-sm text-slate-500">Checklist hak akses halaman per role. Super Admin selalu punya akses penuh dan tidak bisa dibatasi dari sini.</p>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        @foreach($roles as $role)
            <form method="POST" action="{{ route('admin.access-control.update', $role) }}" class="rounded-3xl bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold">{{ $role->label }}</h3>
                        <p class="text-sm text-slate-500">{{ $role->name }}</p>
                    </div>
                    @if($role->name === 'super_admin')
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Locked Full Access</span>
                    @endif
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @foreach($permissions as $permission)
                        <label class="flex items-start gap-3 rounded-2xl border border-slate-200 p-4 {{ $role->name === 'super_admin' ? 'opacity-60' : '' }}">
                            <input
                                type="checkbox"
                                name="permission_ids[]"
                                value="{{ $permission->id }}"
                                class="mt-1 h-4 w-4 rounded border-slate-300"
                                @checked($role->name === 'super_admin' || $role->pagePermissions->contains('id', $permission->id))
                                @disabled($role->name === 'super_admin')
                            >
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">{{ $permission->label }}</span>
                                <span class="block text-xs text-slate-500">{{ $permission->description }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>

                @if($role->name !== 'super_admin')
                    <button type="submit" class="mt-6 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan Hak Akses</button>
                @endif
            </form>
        @endforeach
    </div>
@endsection
