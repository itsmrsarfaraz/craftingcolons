<x-layouts.site :title="'News — Crafting Colons'">
    <section class="section">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Updates</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Company News</h1>
            <p class="mx-auto mt-3 max-w-lg text-ink-400">Announcements, milestones, and what's happening at Crafting Colons.</p>
        </div>

        <div class="mt-10 space-y-4">
            @forelse ($news as $i => $item)
                <a href="{{ route('news.show', $item->slug) }}"
                   class="card card-hover flex flex-col gap-2 p-5 sm:flex-row sm:items-center sm:justify-between"
                   data-reveal data-reveal-delay="{{ min($i, 4) }}">
                    <div>
                        <h2 class="font-semibold text-white">{{ $item->title }}</h2>
                        @if ($item->excerpt)
                            <p class="mt-1 text-sm text-ink-400">{{ Str::limit($item->excerpt, 120) }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 text-xs text-ink-500">{{ $item->published_at->format('M j, Y') }}</span>
                </a>
            @empty
                <div class="card p-10 text-center text-ink-500">No news yet.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $news->links() }}</div>
    </section>
</x-layouts.site>