@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div><h2 class="text-xl font-bold">Testimoni</h2><p class="text-sm text-slate-500">Kelola testimoni asli pelanggan.</p></div>
        <a href="{{ route('admin.testimonials.create') }}" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Tambah Testimoni</a>
    </div>
    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500"><tr><th class="px-6 py-4">Nama</th><th class="px-6 py-4">Rating</th><th class="px-6 py-4">Status</th><th class="px-6 py-4"></th></tr></thead>
            <tbody>
                @forelse($testimonials as $testimonial)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">{{ $testimonial->customer_name }}</td>
                        <td class="px-6 py-4">{{ $testimonial->rating }}/5</td>
                        <td class="px-6 py-4">{{ $testimonial->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-6 py-4 text-right"><a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="mr-3 font-medium text-cyan-700">Edit</a><form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="inline">@csrf @method('DELETE')<button type="submit" class="font-medium text-rose-600">Hapus</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada testimoni.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $testimonials->links() }}</div>
@endsection
