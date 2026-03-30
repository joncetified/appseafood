@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.company-profile.update') }}" class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-2 block text-sm font-semibold">Nama Bisnis</label>
            <input name="business_name" type="text" value="{{ old('business_name', $profile->business_name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Tagline</label>
            <input name="tagline" type="text" value="{{ old('tagline', $profile->tagline) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div class="md:col-span-2">
            <label class="mb-2 block text-sm font-semibold">Tentang Bisnis</label>
            <textarea name="about" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('about', $profile->about) }}</textarea>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Telepon</label>
            <input name="phone" type="text" value="{{ old('phone', $profile->phone) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">WhatsApp</label>
            <input name="whatsapp" type="text" value="{{ old('whatsapp', $profile->whatsapp) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Email</label>
            <input name="email" type="email" value="{{ old('email', $profile->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Alamat</label>
            <textarea name="address" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('address', $profile->address) }}</textarea>
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Jam Hari Kerja</label>
            <input name="weekday_hours" type="text" value="{{ old('weekday_hours', $profile->weekday_hours) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold">Jam Akhir Pekan</label>
            <input name="weekend_hours" type="text" value="{{ old('weekend_hours', $profile->weekend_hours) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
        <div class="md:col-span-2"><button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Simpan Profil Bisnis</button></div>
    </form>
@endsection
