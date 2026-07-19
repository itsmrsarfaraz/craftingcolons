<x-layouts.site :title="$service->seoTitle().' — Crafting Colons'" :description="$service->seoDescription()">
    <section class="section max-w-3xl">
        <div data-reveal>
            @if ($service->icon)
                <span class="text-4xl">{{ $service->icon }}</span>
            @endif
            <h1 class="mt-4 font-display text-3xl font-semibold sm:text-4xl">{{ $service->title }}</h1>
            <p class="mt-3 text-lg text-ink-400">{{ $service->short_description }}</p>
        </div>

        <div class="prose prose-invert mt-8 max-w-none" data-reveal data-reveal-delay="1">
            {!! nl2br(e($service->body)) !!}
        </div>

        <div class="card mt-12 flex flex-col items-center gap-4 p-8 text-center sm:flex-row sm:justify-between sm:text-left" data-reveal data-reveal-delay="2">
            <div>
                <p class="font-display text-lg font-semibold text-white">Need this for your project?</p>
                <p class="mt-1 text-sm text-ink-400">Let's talk about what you're building.</p>
            </div>
            <a href="{{ route('careers.index') }}" class="btn-primary shrink-0">Get in Touch</a>
        </div>
    </section>
</x-layouts.site>