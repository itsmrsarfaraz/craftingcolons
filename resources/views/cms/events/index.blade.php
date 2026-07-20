<x-layouts.site :title="'Events — Crafting Colons'">
    <section class="section">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Community</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Events</h1>
            <p class="mx-auto mt-3 max-w-lg text-ink-400">Meetups, workshops, and training sessions.</p>
        </div>

        <div class="mt-8 flex justify-center gap-2" data-reveal data-reveal-delay="1">
            <a href="{{ route('events.index') }}"
               class="rounded-full px-4 py-1.5 text-xs font-medium {{ request('when', 'upcoming') === 'upcoming' ? 'bg-brand-500 text-ink-950' : 'bg-ink-800 text-ink-300' }}">
                Upcoming
            </a>
            <a href="{{ route('events.index', ['when' => 'past']) }}"
               class="rounded-full px-4 py-1.5 text-xs font-medium {{ request('when') === 'past' ? 'bg-brand-500 text-ink-950' : 'bg-ink-800 text-ink-300' }}">
                Past
            </a>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($events as $i => $event)
                <a href="{{ route('events.show', $event->slug) }}"
                   class="card card-hover p-5" data-reveal data-reveal-delay="{{ min($i, 4) }}">
                    <p class="text-xs font-medium uppercase tracking-wide text-brand-400">{{ $event->starts_at->format('M j, Y') }}</p>
                    <h2 class="mt-2 font-semibold text-white">{{ $event->title }}</h2>
                    <p class="mt-2 text-sm text-ink-400">{{ $event->is_virtual ? 'Virtual' : $event->location }}</p>
                </a>
            @empty
                <div class="card p-10 text-center text-ink-500 sm:col-span-2 lg:col-span-3">No events found.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $events->links() }}</div>
    </section>
</x-layouts.site>