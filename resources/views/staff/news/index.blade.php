<x-layouts.app :title="'Articles — Crafting Colons'">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">News</h1>
            <a href="{{ route('staff.news.create') }}"
               class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-ink-200 transition">
                + New News Post
            </a>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-ink-900 border border-ink-800 rounded-2xl divide-y divide-ink-800 overflow-hidden">
            @forelse ($news as $post)
                <a href="{{ route('staff.news.edit', $news) }}" class="block bg-ink-900 border border-ink-800 rounded-2xl p-6 hover:border-ink-700">
                   class="flex items-center justify-between px-6 py-4 hover:bg-ink-800/40 transition">
                    <div>
                        <p class="font-medium text-ink-100">{{ $post->title }}</p>
                        <p class="text-xs text-ink-500">By {{ $post->author->name }}</p>
                    </div>
                    <span class="text-xs uppercase tracking-wide bg-ink-800 border border-ink-700 rounded-full px-3 py-1 font-medium text-ink-300">
                        {{ $post->status->label() }}
                    </span>
                </a>
            @empty
                <div class="px-6 py-12 text-center text-ink-500 text-sm">
                    No news posts have been created yet.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $news->links() }}
        </div>
    </div>
</x-layouts.app>