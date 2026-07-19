<x-layouts.site :title="'Crafting Colons — Software Development & IT Services, Islamabad'">

    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 -top-40 -z-10 flex justify-center">
            <div class="h-[480px] w-[780px] rounded-full bg-brand-500/20 blur-[120px]"></div>
        </div>

        <div class="section text-center">
            <span class="eyebrow">Islamabad · Est. 2024</span>
            <h1 class="mx-auto mt-4 max-w-3xl font-display text-5xl font-semibold leading-[1.05] sm:text-6xl">
                We build software that <span class="text-brand-400">means business.</span>
            </h1>
            <p class="mx-auto mt-6 max-w-xl text-lg text-ink-300">
                Web platforms, mobile apps, and internal tools — designed, shipped, and maintained by a team that also trains the next generation of developers.
            </p>
            <div class="mt-9 flex items-center justify-center gap-4">
                <a href="{{ route('careers.index') }}" class="btn-primary">View Open Roles</a>
                <a href="{{ route('projects.index') }}" class="btn-secondary">See Our Work →</a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    @if ($stats->isNotEmpty())
        <section class="border-y border-ink-800 bg-ink-900/40">
            <div class="mx-auto grid max-w-5xl grid-cols-2 gap-8 px-6 py-14 sm:grid-cols-4 sm:px-8">
                @foreach ($stats as $stat)
                    <div class="text-center">
                        <p class="font-display text-4xl font-semibold text-white">{{ $stat->value }}</p>
                        <p class="mt-1 text-sm text-ink-400">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Featured Projects -->
    @if ($featuredProjects->isNotEmpty())
        <section class="section">
            <div class="flex items-end justify-between">
                <div>
                    <span class="eyebrow">Portfolio</span>
                    <h2 class="mt-2 font-display text-3xl font-semibold">Recent work</h2>
                </div>
                <a href="{{ route('projects.index') }}" class="text-sm font-medium text-brand-400 hover:text-brand-300">View all →</a>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="card card-hover group overflow-hidden">
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
            <div class="section !py-16">
                <div class="text-center">
                    <span class="eyebrow">Testimonials</span>
                    <h2 class="mt-2 font-display text-3xl font-semibold">What people say</h2>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2">
                    @foreach ($testimonials as $testimonial)
                        <div class="card p-7">
                            <svg class="h-6 w-6 text-brand-500/60" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/></svg>
                            <p class="mt-4 text-ink-200">{{ $testimonial->quote }}</p>
                            <div class="mt-5 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-500/20 text-sm font-semibold text-brand-400">
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
            <div class="flex items-end justify-between">
                <div>
                    <span class="eyebrow">Careers</span>
                    <h2 class="mt-2 font-display text-3xl font-semibold">Open roles</h2>
                </div>
                <a href="{{ route('careers.index') }}" class="text-sm font-medium text-brand-400 hover:text-brand-300">View all →</a>
            </div>

            <div class="mt-10 space-y-3">
                @foreach ($latestJobs as $job)
                    <a href="{{ route('careers.show', $job->slug) }}" class="card card-hover flex items-center justify-between p-5">
                        <div>
                            <p class="font-medium text-white">{{ $job->title }}</p>
                            <p class="mt-1 text-sm text-ink-400">{{ $job->department }} · {{ $job->location ?? 'Remote' }}</p>
                        </div>
                        <span class="rounded-full border border-ink-700 px-3 py-1 text-xs font-medium text-ink-300">
                            {{ $job->employment_type->label() }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Latest Articles + News -->
    @if ($latestArticles->isNotEmpty() || $latestNews->isNotEmpty())
        <section class="section grid gap-12 sm:grid-cols-2">
            @if ($latestArticles->isNotEmpty())
                <div>
                    <span class="eyebrow">Blog</span>
                    <h2 class="mt-2 font-display text-2xl font-semibold">From the blog</h2>
                    <div class="mt-6 space-y-5">
                        @foreach ($latestArticles as $article)
                            <a href="{{ route('articles.show', $article->slug) }}" class="block group">
                                <p class="font-medium text-white transition group-hover:text-brand-400">{{ $article->title }}</p>
                                <p class="mt-1 text-sm text-ink-500">{{ $article->published_at->format('M j, Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($latestNews->isNotEmpty())
                <div>
                    <span class="eyebrow">Updates</span>
                    <h2 class="mt-2 font-display text-2xl font-semibold">Company news</h2>
                    <div class="mt-6 space-y-5">
                        @foreach ($latestNews as $news)
                            <a href="{{ route('news.show', $news->slug) }}" class="block group">
                                <p class="font-medium text-white transition group-hover:text-brand-400">{{ $news->title }}</p>
                                <p class="mt-1 text-sm text-ink-500">{{ $news->published_at->format('M j, Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <!-- CTA -->
    <section class="border-t border-ink-800">
        <div class="section text-center !py-24">
            <h2 class="font-display text-3xl font-semibold sm:text-4xl">Let's build something together.</h2>
            <p class="mx-auto mt-3 max-w-md text-ink-400">Have a project in mind, or want to join the team?</p>
            <a href="{{ route('careers.index') }}" class="btn-primary mt-8">Get in Touch</a>
        </div>
    </section>

</x-layouts.site>