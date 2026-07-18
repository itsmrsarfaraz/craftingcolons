<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Announcements — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-4">
        <h1 class="text-2xl font-semibold">Announcements</h1>

        @forelse ($announcements as $announcement)
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
                <p class="font-medium">{{ $announcement->title }}</p>
                <p class="text-xs text-neutral-500 mt-1">{{ $announcement->published_at->diffForHumans() }}</p>
                <p class="text-sm text-neutral-400 mt-3">{{ $announcement->body }}</p>
            </div>
        @empty
            <p class="text-neutral-500 text-sm">No announcements yet.</p>
        @endforelse
    </div>
</body>
</html>