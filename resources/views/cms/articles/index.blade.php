<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Articles — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-3xl font-semibold">Articles</h1>

        <div class="grid gap-4">
            @foreach ($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}"
                   class="block bg-neutral-900 border border-neutral-800 rounded-2xl p-6 hover:border-neutral-700 transition">
                    <h2 class="text-lg font-semibold">{{ $article->title }}</h2>
                    <p class="text-sm text-neutral-400 mt-2">{{ $article->excerpt }}</p>
                    <p class="text-xs text-neutral-500 mt-3">
                        By {{ $article->author->name }} · {{ $article->published_at->format('M j, Y') }}
                    </p>
                </a>
            @endforeach
        </div>

        {{ $articles->links() }}
    </div>
</body>
</html>