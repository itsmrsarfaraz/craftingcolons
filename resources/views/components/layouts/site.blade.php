<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Crafting Colons' }}</title>
    <meta name="description" content="{{ $description ?? 'Crafting Colons builds scalable software, mobile apps, and digital platforms from Islamabad, Pakistan.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" defer onload="lucide.createIcons()"></script>
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="flex min-h-full flex-col bg-ink-950">
    @include('partials.nav')

    <main class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')
</body>
</html>