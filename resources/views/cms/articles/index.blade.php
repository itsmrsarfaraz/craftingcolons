<x-layouts.site :title="'Articles — Crafting Colons'">
    <section class="section">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Knowledge Hub</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Articles</h1>
            <p class="mx-auto mt-3 max-w-lg text-ink-400">Engineering notes, lessons learned, and how we build.</p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($articles as $i => $article)
                <a href="{{ route('articles.show', $article->slug) }}"
                   class="card card-hover overflow-hidden" data-reveal data-reveal-delay="{{ min($i, 4) }}">
                    @if ($article->featuredImage())
                        <div class="aspect-[16/9] overflow-hidden bg-ink-800">
                            <img src="{{ $article->featuredImage()->url() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $article->title }}">
                        </div>
                    @endif
                    <div class="p-5">
                        <p class="text-xs font-medium text-ink-500">{{ $article->published_at->format('M j, Y') }} · {{ $article->author->name }}</p>
                        <h2 class="mt-2 font-semibold text-white">{{ $article->title }}</h2>
                        @if ($article->excerpt)
                            <p class="mt-2 text-sm text-ink-400">{{ Str::limit($article->excerpt, 100) }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="card p-10 text-center text-ink-500 sm:col-span-2 lg:col-span-3">No articles published yet.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $articles->links() }}</div>
    </section>
</x-layouts.site>