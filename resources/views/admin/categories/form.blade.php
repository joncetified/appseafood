@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-6 rounded-3xl bg-white p-6 shadow-sm">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div>
            <label class="mb-2 block text-sm font-semibold">Nama</label>
            <input name="name" type="text" value="{{ old('name', $category->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Deskripsi</label>
            <textarea name="description" class="w-full rounded-2xl border border-slate-200 px-4 py-3" rows="4">{{ old('description', $category->description) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
            Aktif
        </label>
        <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan</button>
    </form>
@endsection
