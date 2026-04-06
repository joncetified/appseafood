@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        @if($user->exists) @method('PUT') @endif
        <div>
            <label class="mb-2 block text-sm font-semibold">Nama</label>
            <input name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">WhatsApp</label>
            <input name="whatsapp_number" type="text" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Role</label>
            <select name="role_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Password {{ $user->exists ? '(kosongkan jika tidak ganti)' : '' }}</label>
            <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" {{ $user->exists ? '' : 'required' }}>
        </div>
        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->exists ? $user->is_active : true))>
            User aktif
        </label>
        <div class="md:col-span-2"><button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan User</button></div>
    </form>
@endsection
