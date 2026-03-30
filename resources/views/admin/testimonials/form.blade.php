@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="space-y-6 rounded-3xl bg-white p-6 shadow-sm">
        @csrf
        @if($testimonial->exists) @method('PUT') @endif
        <div>
            <label class="mb-2 block text-sm font-semibold">Nama Pelanggan</label>
            <input name="customer_name" type="text" value="{{ old('customer_name', $testimonial->customer_name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Rating</label>
            <input name="rating" type="number" min="1" max="5" value="{{ old('rating', $testimonial->rating ?? 5) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Isi Testimoni</label>
            <textarea name="content" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>{{ old('content', $testimonial->content) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>Aktif</label>
        <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan Testimoni</button>
    </form>
@endsection
