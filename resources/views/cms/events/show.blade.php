<x-layouts.site :title="$event->seoTitle().' — Crafting Colons'" :description="$event->seoDescription()">
    <section class="section max-w-2xl">
        <a href="{{ route('events.index') }}" class="text-sm text-ink-400 hover:text-white" data-reveal>← All events</a>

        <div class="mt-4" data-reveal data-reveal-delay="1">
            <h1 class="font-display text-3xl font-semibold sm:text-4xl">{{ $event->title }}</h1>
            <p class="mt-3 text-sm text-ink-400">
                {{ $event->starts_at->format('M j, Y g:i A') }} – {{ $event->ends_at->format('g:i A') }}
                · {{ $event->is_virtual ? 'Virtual' : $event->location }}
            </p>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="prose prose-invert mt-8 max-w-none" data-reveal data-reveal-delay="2">
            <p>{{ $event->description }}</p>
        </div>

        <div class="card mt-10 flex items-center justify-between p-6" data-reveal data-reveal-delay="3">
            <p class="text-sm text-ink-400">
                {{ $event->registrations_count }}{{ $event->max_attendees ? ' / '.$event->max_attendees : '' }} registered
            </p>

            @auth
                @if ($event->isUpcoming() && ! $event->isFull())
                    <form method="POST" action="{{ route('events.register', $event->slug) }}">
                        @csrf
                        <button class="btn-primary">Register</button>
                    </form>
                @elseif ($event->isFull())
                    <span class="text-sm font-medium text-red-400">Event Full</span>
                @else
                    <span class="text-sm text-ink-500">This event has ended</span>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-secondary">Log in to register</a>
            @endauth
        </div>
    </section>
</x-layouts.site>