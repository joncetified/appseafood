@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $promotion->exists ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}" class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        @if($promotion->exists) @method('PUT') @endif
        <div class="md:col-span-2">
            <label class="mb-2 block text-sm font-semibold">Judul Promo</label>
            <input name="title" type="text" value="{{ old('title', $promotion->title) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Mulai</label>
            <input name="start_date" type="date" value="{{ old('start_date', optional($promotion->start_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Selesai</label>
            <input name="end_date" type="date" value="{{ old('end_date', optional($promotion->end_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div class="md:col-span-2">
            <label class="mb-2 block text-sm font-semibold">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('description', $promotion->description) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $promotion->is_active ?? true) ? 'checked' : '' }}>Aktif</label>
        <div class="md:col-span-2"><button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan Promo</button></div>
    </form>
@endsection
