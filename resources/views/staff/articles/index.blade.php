<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Articles — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Articles</h1>
            <a href="{{ route('staff.categories.index') }}" class="btn-secondary !px-4 !py-2 text-xs">Manage Categories</a>
            <a href="{{ route('staff.articles.create') }}"
               class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-neutral-200">
                + New Article
            </a>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl divide-y divide-neutral-800">
            @foreach ($articles as $article)
                <a href="{{ route('staff.articles.edit', $article) }}"
                   class="flex items-center justify-between px-6 py-4 hover:bg-neutral-800/40">
                    <div>
                        <p class="font-medium">{{ $article->title }}</p>
                        <p class="text-xs text-neutral-500">{{ $article->author->name }}</p>
                    </div>
                    <span class="text-xs uppercase tracking-wide bg-neutral-800 rounded-full px-3 py-1">
                        {{ $article->status->label() }}
                    </span>
                </a>
            @endforeach
        </div>

        {{ $articles->links() }}
    </div>
</body>
</html>