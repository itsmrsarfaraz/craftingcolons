<x-layouts.site :title="'Crafting Colons — Software Development & IT Services, Islamabad'">

    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 -top-32 -z-10 flex justify-center">
            <div class="h-[320px] w-[90vw] max-w-[780px] rounded-full bg-brand-500/20 blur-[100px] sm:h-[480px]"></div>
        </div>

        <div class="mx-auto max-w-4xl px-4 pt-16 pb-14 text-center sm:px-6 sm:pt-24 sm:pb-20 lg:px-8">
            <span class="eyebrow" data-reveal>Islamabad · Est. 2024</span>
            <h1 class="mx-auto mt-4 max-w-3xl font-display text-4xl font-semibold leading-[1.1] sm:text-5xl lg:text-6xl lg:leading-[1.05]" data-reveal data-reveal-delay="1">
                We build software that <span class="text-brand-400">means business.</span>
            </h1>
            <p class="mx-auto mt-5 max-w-xl text-base text-ink-300 sm:mt-6 sm:text-lg" data-reveal data-reveal-delay="2">
                Web platforms, mobile apps, and internal tools — designed, shipped, and maintained by a team that also trains the next generation of developers.
            </p>
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:mt-9 sm:flex-row sm:gap-4" data-reveal data-reveal-delay="3">
                <a href="{{ route('careers.index') }}" class="btn-primary w-full sm:w-auto">View Open Roles</a>
                <a href="{{ route('projects.index') }}" class="btn-secondary w-full sm:w-auto">See Our Work →</a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    @if ($stats->isNotEmpty())
        <section class="border-y border-ink-800 bg-ink-900/40">
            <div class="mx-auto grid max-w-5xl grid-cols-2 gap-6 px-4 py-10 sm:gap-8 sm:px-6 sm:py-14 lg:grid-cols-4 lg:px-8">
                @foreach ($stats as $i => $stat)
                    <div class="text-center" data-reveal data-reveal-delay="{{ min($i, 4) }}">
                        <p class="font-display text-3xl font-semibold text-white sm:text-4xl">{{ $stat->value }}</p>
                        <p class="mt-1 text-xs text-ink-400 sm:text-sm">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Services -->
    <!-- Services -->
    @if ($services->isNotEmpty())
        <section class="section">
            <div class="text-center sm:text-left" data-reveal>
                <span class="eyebrow">What we do</span>
                <h2 class="mt-2 font-display text-2xl font-semibold sm:text-3xl">Services built to ship</h2>
            </div>

            <div class="mt-8 grid gap-5 sm:mt-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($services as $i => $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="card card-hover p-6" data-reveal data-reveal-delay="{{ $i }}">
                        @if ($service->icon)<span class="text-2xl">{{ $service->icon }}</span>@endif
                        <p class="mt-4 font-semibold text-white">{{ $service->title }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-ink-400">{{ $service->short_description }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Why Choose Us -->
    <section class="border-y border-ink-800 bg-ink-900/40">
        <div class="section !py-14 sm:!py-16">
            <div class="text-center" data-reveal>
                <span class="eyebrow">Why Crafting Colons</span>
                <h2 class="mt-2 font-display text-2xl font-semibold sm:text-3xl">Built different, on purpose</h2>
            </div>

            <div class="mt-8 grid gap-6 sm:mt-10 sm:grid-cols-3">
                @php
                    $reasons = [
                        ['title' => 'Senior-led delivery', 'body' => 'Every project is architected by senior engineers, not left to guesswork.'],
                        ['title' => 'We train who we hire', 'body' => 'Our internship pipeline means the team you work with keeps getting stronger.'],
                        ['title' => 'Transparent process', 'body' => 'Clear milestones, real communication, no black-box development.'],
                    ];
                @endphp
                @foreach ($reasons as $i => $reason)
                    <div class="text-center sm:text-left" data-reveal data-reveal-delay="{{ $i }}">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-brand-500/15 text-brand-400 sm:mx-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <p class="mt-4 font-semibold text-white">{{ $reason['title'] }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-ink-400">{{ $reason['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Technologies marquee -->
    @if ($technologies->isNotEmpty())
        <section class="py-14 sm:py-16">
            <p class="eyebrow text-center">Our stack</p>
            <div class="relative mt-6 overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
                <div class="flex w-max animate-marquee gap-10 whitespace-nowrap">
                    @foreach ($technologies->concat($technologies) as $tech)
                        <span class="text-lg font-medium text-ink-500">{{ $tech->name }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Projects -->
    @if ($featuredProjects->isNotEmpty())
        <section class="section">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div>
                    <span class="eyebrow">Portfolio</span>
                    <h2 class="mt-2 font-display text-2xl font-semibold sm:text-3xl">Recent work</h2>
                </div>
                <a href="{{ route('projects.index') }}" class="text-sm font-medium text-brand-400 hover:text-brand-300">View all →</a>
            </div>

            <div class="mt-8 grid gap-5 sm:mt-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $i => $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="card card-hover group overflow-hidden" data-reveal data-reveal-delay="{{ $i }}">
                        <div class="aspect-[4/3] overflow-hidden bg-ink-800">
                            @if ($project->featuredImage())
                                <img src="{{ $project->featuredImage()->url() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $project->title }}">
                            @else
                                <div class="flex h-full items-center justify-center text-ink-600">
                                    <span class="font-display text-2xl">{{ Str::limit($project->title, 1, '') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-medium uppercase tracking-wide text-brand-400">{{ $project->project_type->label() }}</p>
                            <p class="mt-1 font-semibold text-white">{{ $project->title }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Testimonials -->
    @if ($testimonials->isNotEmpty())
        <section class="border-y border-ink-800 bg-ink-900/40">
            <div class="section !py-14 sm:!py-16">
                <div class="text-center" data-reveal>
                    <span class="eyebrow">Testimonials</span>
                    <h2 class="mt-2 font-display text-2xl font-semibold sm:text-3xl">What people say</h2>
                </div>

                <div class="mt-8 grid gap-5 sm:mt-10 sm:grid-cols-2">
                    @foreach ($testimonials as $i => $testimonial)
                        <div class="card p-6 sm:p-7" data-reveal data-reveal-delay="{{ $i }}">
                            <svg class="h-6 w-6 text-brand-500/60" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/></svg>
                            <p class="mt-4 text-sm text-ink-200 sm:text-base">{{ $testimonial->quote }}</p>
                            <div class="mt-5 flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-500/20 text-sm font-semibold text-brand-400">
                                    {{ Str::substr($testimonial->author_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $testimonial->author_name }}</p>
                                    <p class="text-xs text-ink-500">
                                        {{ $testimonial->author_role }}{{ $testimonial->author_company ? ' · '.$testimonial->author_company : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Latest Jobs -->
    @if ($latestJobs->isNotEmpty())
        <section class="section">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div>
                    <span class="eyebrow">Careers</span>
                    <h2 class="mt-2 font-display text-2xl font-semibold sm:text-3xl">Open roles</h2>
                </div>
                <a href="{{ route('careers.index') }}" class="text-sm font-medium text-brand-400 hover:text-brand-300">View all →</a>
            </div>

            <div class="mt-8 space-y-3 sm:mt-10">
                @foreach ($latestJobs as $i => $job)
                    <a href="{{ route('careers.show', $job->slug) }}" class="card card-hover flex flex-col gap-2 p-5 sm:flex-row sm:items-center sm:justify-between" data-reveal data-reveal-delay="{{ $i }}">
                        <div>
                            <p class="font-medium text-white">{{ $job->title }}</p>
                            <p class="mt-1 text-sm text-ink-400">{{ $job->department }} · {{ $job->location ?? 'Remote' }}</p>
                        </div>
                        <span class="w-fit rounded-full border border-ink-700 px-3 py-1 text-xs font-medium text-ink-300">
                            {{ $job->employment_type->label() }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Upcoming Events -->
    @if ($upcomingEvents->isNotEmpty())
        <section class="border-y border-ink-800 bg-ink-900/40">
            <div class="section !py-14 sm:!py-16">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                    <div>
                        <span class="eyebrow">Community</span>
                        <h2 class="mt-2 font-display text-2xl font-semibold sm:text-3xl">Upcoming events</h2>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-sm font-medium text-brand-400 hover:text-brand-300">View all →</a>
                </div>

                <div class="mt-8 grid gap-5 sm:mt-10 sm:grid-cols-3">
                    @foreach ($upcomingEvents as $i => $event)
                        <a href="{{ route('events.show', $event->slug) }}" class="card card-hover p-5" data-reveal data-reveal-delay="{{ $i }}">
                            <p class="text-xs font-medium uppercase tracking-wide text-brand-400">{{ $event->starts_at->format('M j, Y') }}</p>
                            <p class="mt-2 font-semibold text-white">{{ $event->title }}</p>
                            <p class="mt-2 text-sm text-ink-400">{{ $event->is_virtual ? 'Virtual' : $event->location }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Latest Articles + News -->
    @if ($latestArticles->isNotEmpty() || $latestNews->isNotEmpty())
        <section class="section grid gap-10 sm:grid-cols-2 sm:gap-12">
            @if ($latestArticles->isNotEmpty())
                <div data-reveal>
                    <span class="eyebrow">Blog</span>
                    <h2 class="mt-2 font-display text-xl font-semibold sm:text-2xl">From the blog</h2>
                    <div class="mt-5 space-y-4 sm:mt-6 sm:space-y-5">
                        @foreach ($latestArticles as $article)
                            <a href="{{ route('articles.show', $article->slug) }}" class="group block">
                                <p class="font-medium text-white transition group-hover:text-brand-400">{{ $article->title }}</p>
                                <p class="mt-1 text-sm text-ink-500">{{ $article->published_at->format('M j, Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($latestNews->isNotEmpty())
                <div data-reveal data-reveal-delay="1">
                    <span class="eyebrow">Updates</span>
                    <h2 class="mt-2 font-display text-xl font-semibold sm:text-2xl">Company news</h2>
                    <div class="mt-5 space-y-4 sm:mt-6 sm:space-y-5">
                        @foreach ($latestNews as $news)
                            <a href="{{ route('news.show', $news->slug) }}" class="group block">
                                <p class="font-medium text-white transition group-hover:text-brand-400">{{ $news->title }}</p>
                                <p class="mt-1 text-sm text-ink-500">{{ $news->published_at->format('M j, Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <!-- Newsletter -->
    <section class="section !py-14 sm:!py-16">
        <div class="card mx-auto flex max-w-3xl flex-col items-center gap-5 p-8 text-center sm:flex-row sm:justify-between sm:text-left" data-reveal>
            <div>
                <p class="font-display text-xl font-semibold text-white">Stay in the loop</p>
                <p class="mt-1 text-sm text-ink-400">Product updates, hiring news, and articles — no spam.</p>
            </div>
            <form method="POST" action="#" class="flex w-full max-w-sm gap-2 sm:w-auto">
                @csrf
                <input type="email" name="email" required placeholder="you@email.com"
                    class="w-full rounded-full border border-ink-600 bg-ink-800 px-4 py-2.5 text-sm text-white placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500/50">
                <button type="submit" class="btn-primary !px-5 !py-2.5 shrink-0">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="border-t border-ink-800">
        <div class="section text-center !py-16 sm:!py-24">
            <h2 class="font-display text-2xl font-semibold sm:text-3xl lg:text-4xl" data-reveal>Let's build something together.</h2>
            <p class="mx-auto mt-3 max-w-md text-sm text-ink-400 sm:text-base" data-reveal data-reveal-delay="1">Have a project in mind, or want to join the team?</p>
            <a href="{{ route('careers.index') }}" class="btn-primary mt-7 sm:mt-8" data-reveal data-reveal-delay="2">Get in Touch</a>
        </div>
    </section>

</x-layouts.site>