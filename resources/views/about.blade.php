<x-layouts.site :title="'About Us — Crafting Colons'">
    <section class="section max-w-3xl">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Our Story</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Building software, growing people.</h1>
            <p class="mx-auto mt-4 max-w-xl text-ink-400">
                Crafting Colons was founded in Islamabad in 2024 with a simple idea: build real software for real clients,
                while training the developers, designers, and photographers who'll shape the next generation of tech talent in Pakistan.
            </p>
        </div>

        <div class="mt-14 grid gap-6 sm:grid-cols-2" data-reveal data-reveal-delay="1">
            <div class="card p-6">
                <span class="text-2xl">🎯</span>
                <p class="mt-3 font-semibold text-white">Our Mission</p>
                <p class="mt-2 text-sm leading-relaxed text-ink-400">
                    Deliver production-grade software while creating a pipeline of skilled, senior-mentored engineers.
                </p>
            </div>
            <div class="card p-6">
                <span class="text-2xl">🔭</span>
                <p class="mt-3 font-semibold text-white">Our Vision</p>
                <p class="mt-2 text-sm leading-relaxed text-ink-400">
                    To become the region's most trusted partner for scalable, well-engineered digital products.
                </p>
            </div>
        </div>

        <div class="mt-14" data-reveal data-reveal-delay="2">
            <h2 class="text-center font-display text-2xl font-semibold">Our Values</h2>
            <div class="mt-8 grid gap-6 sm:grid-cols-3">
                @foreach ([
                    ['icon' => '🛠️', 'title' => 'Craft over shortcuts', 'body' => 'We build things properly, even when it takes longer.'],
                    ['icon' => '🤝', 'title' => 'Transparent by default', 'body' => 'Clients and teammates always know where things stand.'],
                    ['icon' => '📈', 'title' => 'Grow the people, not just the product', 'body' => 'Every project is also a mentoring opportunity.'],
                ] as $value)
                    <div class="text-center">
                        <span class="text-2xl">{{ $value['icon'] }}</span>
                        <p class="mt-3 font-semibold text-white">{{ $value['title'] }}</p>
                        <p class="mt-2 text-sm text-ink-400">{{ $value['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mt-14 flex flex-col items-center gap-4 p-8 text-center sm:flex-row sm:justify-between sm:text-left" data-reveal data-reveal-delay="3">
            <div>
                <p class="font-display text-lg font-semibold text-white">Want to be part of the story?</p>
                <p class="mt-1 text-sm text-ink-400">We're always looking for driven people to join the team.</p>
            </div>
            <a href="{{ route('careers.index') }}" class="btn-primary shrink-0">View Open Roles</a>
        </div>
    </section>
</x-layouts.site>