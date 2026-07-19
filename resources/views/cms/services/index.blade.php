<x-layouts.site :title="'Services — Crafting Colons'">
    <section class="section">
        <div class="text-center" data-reveal>
            <span class="eyebrow">What we do</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Services</h1>
            <p class="mx-auto mt-3 max-w-lg text-ink-400">
                From idea to production — the capabilities we bring to every engagement.
            </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $i => $service)
                <a href="{{ route('services.show', $service->slug) }}"
                   class="card card-hover p-6" data-reveal data-reveal-delay="{{ min($i, 4) }}">
                    @if ($service->icon)
                        <span class="text-3xl">{{ $service->icon }}</span>
                    @endif
                    <p class="mt-4 font-semibold text-white">{{ $service->title }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-ink-400">{{ $service->short_description }}</p>
                    <span class="mt-4 inline-block text-sm font-medium text-brand-400">Learn more →</span>
                </a>
            @endforeach
        </div>
    </section>
</x-layouts.site>