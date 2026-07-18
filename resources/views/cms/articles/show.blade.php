<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $article->seoTitle() }} — Crafting Colons</title>
    <meta name="description" content="{{ $article->seoDescription() }}">
    @if ($article->canonical_url)
        <link rel="canonical" href="{{ $article->canonical_url }}">
    @endif
    <meta property="og:title" content="{{ $article->seoTitle() }}">
    <meta property="og:description" content="{{ $article->seoDescription() }}">
    @if ($article->featuredImage())
        <meta property="og:image" content="{{ $article->featuredImage()->url() }}">
    @endif
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <article class="max-w-2xl mx-auto space-y-6">
        @if ($article->featuredImage())
            <img src="{{ $article->featuredImage()->url() }}" class="rounded-2xl w-full" alt="{{ $article->title }}">
        @endif

        <div>
            <h1 class="text-3xl font-semibold">{{ $article->title }}</h1>
            <p class="text-sm text-neutral-500 mt-2">
                By {{ $article->author->name }} · {{ $article->published_at->format('M j, Y') }}
            </p>
        </div>

        <div class="prose prose-invert max-w-none">
            {!! nl2br(e($article->body)) !!}
        </div>

        <div class="flex gap-2 flex-wrap pt-4 border-t border-neutral-800">
            @foreach ($article->tags as $tag)
                <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}"
                   class="text-xs bg-neutral-800 rounded-full px-3 py-1 hover:bg-neutral-700">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </article>
</body>
</html>