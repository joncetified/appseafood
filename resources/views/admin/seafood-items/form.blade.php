@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.seafood-items.update', $item) : route('admin.seafood-items.store') }}" class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div>
            <label class="mb-2 block text-sm font-semibold">Kategori</label>
            <select name="category_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Nama Menu</label>
            <input name="name" type="text" value="{{ old('name', $item->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div class="md:col-span-2">
            <label class="mb-2 block text-sm font-semibold">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('description', $item->description) }}</textarea>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Harga</label>
            <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $item->price) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">URL / Path Gambar</label>
            <input name="image_path" type="text" value="{{ old('image_path', $item->image_path) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            <p class="mt-2 text-xs text-slate-500">Bisa isi link gambar langsung dari `Copy image address` atau path file di storage.</p>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Level Pedas</label>
            <input name="spicy_level" type="number" min="0" max="5" value="{{ old('spicy_level', $item->spicy_level ?? 0) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div class="flex items-center gap-6 pt-8">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_available" value="1" {{ old('is_available', $item->is_available ?? true) ? 'checked' : '' }}>Tersedia</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured ?? false) ? 'checked' : '' }}>Featured</label>
        </div>
        <div class="md:col-span-2"><button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan Menu</button></div>
    </form>
@endsection
