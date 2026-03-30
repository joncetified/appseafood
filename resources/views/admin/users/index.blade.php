@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div><h2 class="text-xl font-bold">User & Role</h2><p class="text-sm text-slate-500">Kelola akun sistem termasuk super admin.</p></div>
        <a href="{{ route('admin.users.create') }}" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Tambah User</a>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr><th class="px-6 py-4">Nama</th><th class="px-6 py-4">Email</th><th class="px-6 py-4">Role</th><th class="px-6 py-4"></th></tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->role?->label ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="mr-3 font-medium text-cyan-700">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">@csrf @method('DELETE')<button type="submit" class="font-medium text-rose-600">Hapus</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $users->links() }}</div>
@endsection
