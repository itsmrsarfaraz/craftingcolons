<x-layouts.app :title="'Articles — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-semibold text-white">Articles</h1>
            <div class="flex gap-2">
                <a href="{{ route('staff.categories.index') }}" class="btn-secondary !px-4 !py-2 text-xs">Categories</a>
                <a href="{{ route('staff.articles.create') }}" class="btn-primary !px-4 !py-2 text-xs">+ New Article</a>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="card mt-6 divide-y divide-ink-800">
            @foreach ($articles as $article)
                <a href="{{ route('staff.articles.edit', $article) }}" class="flex items-center justify-between px-6 py-4 hover:bg-ink-800/40">
                    <div>
                        <p class="font-medium text-white">{{ $article->title }}</p>
                        <p class="text-xs text-ink-500">{{ $article->author->name }}</p>
                    </div>
                    <span class="rounded-full border border-ink-700 px-3 py-1 text-xs text-ink-300">{{ $article->status->label() }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-6">{{ $articles->links() }}</div>
    </div>
</x-layouts.app>