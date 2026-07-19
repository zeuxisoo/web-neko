<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Neko</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css2?family=DM+Sans:opsz@9..40&family=Noto+Sans+TC&family=Fira+Code&display=swap" rel="stylesheet">
<link rel="icon" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg"><text y="28" font-size="32">🐱</text></svg>'>
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
@endif
</head>
<body class="bg-background overscroll-none font-sans antialiased">
    <div id="neko">
        <div class="flex h-screen w-full items-center justify-center">
            It is work?
        </div>
    </div>
</body>
</html>
