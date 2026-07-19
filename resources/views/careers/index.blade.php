<x-layouts.site :title="'Careers — Crafting Colons'">
    <section class="section">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Join us</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Open Positions</h1>
            <p class="mx-auto mt-3 max-w-lg text-ink-400">Build real software, ship real work, grow with a senior-led team.</p>
        </div>

        <div class="mt-10 space-y-3">
            @forelse ($jobs as $i => $job)
                <a href="{{ route('careers.show', $job->slug) }}"
                   class="card card-hover flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between"
                   data-reveal data-reveal-delay="{{ min($i, 4) }}">
                    <div>
                        <h2 class="font-semibold text-white">{{ $job->title }}</h2>
                        <p class="mt-1 text-sm text-ink-400">{{ $job->department }} · {{ $job->location ?? 'Remote' }}</p>
                    </div>
                    <span class="w-fit rounded-full border border-ink-700 px-3 py-1 text-xs font-medium text-ink-300">
                        {{ $job->employment_type->label() }}
                    </span>
                </a>
            @empty
                <div class="card p-10 text-center text-ink-500">No open positions right now. Check back soon.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $jobs->links() }}</div>
    </section>
</x-layouts.site>