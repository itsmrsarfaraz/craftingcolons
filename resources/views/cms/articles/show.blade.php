<x-layouts.site :title="$article->seoTitle().' — Crafting Colons'" :description="$article->seoDescription()">
    <section class="section max-w-2xl">
        <a href="{{ route('articles.index') }}" class="text-sm text-ink-400 hover:text-white" data-reveal>← All articles</a>

        <div class="mt-4" data-reveal data-reveal-delay="1">
            <h1 class="font-display text-3xl font-semibold sm:text-4xl">{{ $article->title }}</h1>
            <p class="mt-3 text-sm text-ink-500">By {{ $article->author->name }} · {{ $article->published_at->format('M j, Y') }}</p>
        </div>

        @if ($article->featuredImage())
            <div class="mt-8 overflow-hidden rounded-2xl" data-reveal data-reveal-delay="2">
                <img src="{{ $article->featuredImage()->url() }}" class="w-full" alt="{{ $article->title }}">
            </div>
        @endif

        <div class="prose prose-invert mt-8 max-w-none" data-reveal data-reveal-delay="3">
            {!! nl2br(e($article->body)) !!}
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-2 border-t border-ink-800 pt-6" data-reveal data-reveal-delay="4">
                @foreach ($article->tags as $tag)
                    <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}"
                       class="rounded-full bg-ink-800 px-3 py-1 text-xs text-ink-300 hover:bg-ink-700">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.site>