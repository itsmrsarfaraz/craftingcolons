<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>News — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-3xl font-semibold">News</h1>

        <div class="grid gap-4">
            @foreach ($news as $news)
                <a href="{{ route('news.show', $news->slug) }}"
                   class="block bg-neutral-900 border border-neutral-800 rounded-2xl p-6 hover:border-neutral-700 transition">
                    <h2 class="text-lg font-semibold">{{ $news->title }}</h2>
                    <p class="text-sm text-neutral-400 mt-2">{{ $news->excerpt }}</p>
                    <p class="text-xs text-neutral-500 mt-3">
                        By {{ $news->author->name }} · {{ $news->published_at->format('M j, Y') }}
                    </p>
                </a>
            @endforeach
        </div>

        {{ $news->links() }}
    </div>
</body>
</html>