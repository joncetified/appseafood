<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appData['profile']->business_name ?? 'Seafood' }}</title>
    <meta name="theme-color" content="#0f172a">
    <meta name="application-name" content="{{ $appData['profile']->business_name ?? 'Seafood' }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ $appData['profile']->business_name ?? 'Seafood' }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.svg">
    <script>
        window.__SEAFOOD_APP_DATA__ = @json($appData ?? []);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-slate-50 antialiased">
    <div id="app"></div>
</body>
</html>
