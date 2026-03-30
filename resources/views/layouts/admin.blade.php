<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Seafood Admin' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        <aside class="hidden w-72 bg-slate-900 p-6 text-slate-100 lg:block">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-black">Seafood Admin</a>
            <p class="mt-2 text-sm text-slate-400">{{ auth()->user()?->role?->label }}</p>

            <nav class="mt-8 space-y-2 text-sm">
                <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.dashboard') }}">Dashboard</a>

                @if(auth()->user()?->hasRole(['super_admin', 'admin']))
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.categories.index') }}">Kategori</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.seafood-items.index') }}">Menu Seafood</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.promotions.index') }}">Promo</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.testimonials.index') }}">Testimoni</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.company-profile.edit') }}">Profil Bisnis</a>
                @endif

                @if(auth()->user()?->hasRole(['super_admin']))
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.users.index') }}">User & Role</a>
                @endif

                @if(auth()->user()?->hasRole(['super_admin', 'admin', 'kasir']))
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.orders.index') }}">Pesanan</a>
                @endif

                @if(auth()->user()?->hasRole(['super_admin', 'admin', 'manager']))
                    <a class="block rounded-xl px-4 py-3 hover:bg-slate-800" href="{{ route('admin.reports.index') }}">Laporan</a>
                @endif
            </nav>
        </aside>

        <main class="flex-1">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div>
                        <h1 class="text-xl font-bold">{{ $heading ?? 'Dashboard' }}</h1>
                        @isset($subheading)
                            <p class="text-sm text-slate-500">{{ $subheading }}</p>
                        @endisset
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium">Lihat Situs</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
