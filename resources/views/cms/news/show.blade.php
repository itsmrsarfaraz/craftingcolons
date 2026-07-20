<x-layouts.site :title="$news->seoTitle().' — Crafting Colons'" :description="$news->seoDescription()">
    <section class="section max-w-2xl">
        <a href="{{ route('news.index') }}" class="text-sm text-ink-400 hover:text-white" data-reveal>← All news</a>

        <div class="mt-4" data-reveal data-reveal-delay="1">
            <span class="eyebrow">{{ $news->published_at->format('M j, Y') }}</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">{{ $news->title }}</h1>
        </div>

        <div class="prose prose-invert mt-8 max-w-none" data-reveal data-reveal-delay="2">
            {!! nl2br(e($news->body)) !!}
        </div>
    </section>
</x-layouts.site>