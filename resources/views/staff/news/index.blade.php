<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">News</h1>
            <a href="{{ route('staff.news.create') }}"
               class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-neutral-200 transition">
                + New News Post
            </a>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl divide-y divide-neutral-800 overflow-hidden">
            @forelse ($news as $post)
                <a href="{{ route('staff.news.edit', $post) }}"
                   class="flex items-center justify-between px-6 py-4 hover:bg-neutral-800/40 transition">
                    <div>
                        <p class="font-medium text-neutral-100">{{ $post->title }}</p>
                        <p class="text-xs text-neutral-500">By {{ $post->author->name }}</p>
                    </div>
                    <span class="text-xs uppercase tracking-wide bg-neutral-800 border border-neutral-700 rounded-full px-3 py-1 font-medium text-neutral-300">
                        {{ $post->status->label() }}
                    </span>
                </a>
            @empty
                <div class="px-6 py-12 text-center text-neutral-500 text-sm">
                    No news posts have been created yet.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $news->links() }}
        </div>
    </div>
</body>
</html>