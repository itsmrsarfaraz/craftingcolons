<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Crafting Colons — Software Development & IT Services, Islamabad</title>
    <meta name="description" content="Crafting Colons builds scalable software, mobile apps, and digital platforms, and trains the next generation of developers in Islamabad, Pakistan.">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white">
    <!-- Hero -->
    <section class="max-w-4xl mx-auto text-center py-24 px-4">
        <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight">
            We build software that means business.
        </h1>
        <p class="text-neutral-400 mt-4 text-lg">
            Crafting Colons designs and ships web platforms, mobile apps, and internal tools — then trains the developers who'll maintain them.
        </p>
        <div class="flex items-center justify-center gap-3 mt-8">
            <a href="{{ route('careers.index') }}" class="bg-white text-neutral-950 font-medium rounded-lg px-5 py-3 hover:bg-neutral-200 transition">
                View Open Roles
            </a>
            <a href="{{ route('projects.index') }}" class="border border-neutral-700 rounded-lg px-5 py-3 hover:bg-neutral-900 transition">
                See Our Work
            </a>
        </div>
    </section>

    <!-- Stats -->
    @if ($stats->isNotEmpty())
        <section class="max-w-4xl mx-auto px-4 py-12 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
            @foreach ($stats as $stat)
                <div>
                    <p class="text-3xl font-semibold">{{ $stat->value }}</p>
                    <p class="text-sm text-neutral-500 mt-1">{{ $stat->label }}</p>
                </div>
            @endforeach
        </section>
    @endif

    <!-- Featured Projects -->
    @if ($featuredProjects->isNotEmpty())
        <section class="max-w-4xl mx-auto px-4 py-16">
            <h2 class="text-2xl font-semibold mb-6">Recent Work</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}"
                       class="block bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden hover:border-neutral-700 transition">
                        @if ($project->featuredImage())
                            <img src="{{ $project->featuredImage()->url() }}" class="w-full h-32 object-cover" alt="{{ $project->title }}">
                        @endif
                        <div class="p-4">
                            <p class="font-medium text-sm">{{ $project->title }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Testimonials -->
    @if ($testimonials->isNotEmpty())
        <section class="max-w-3xl mx-auto px-4 py-16">
            <h2 class="text-2xl font-semibold mb-6 text-center">What people say</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach ($testimonials as $testimonial)
                    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
                        <p class="text-neutral-300 italic">"{{ $testimonial->quote }}"</p>
                        <p class="text-sm text-neutral-500 mt-4">
                            {{ $testimonial->author_name }}
                            @if ($testimonial->author_role) · {{ $testimonial->author_role }} @endif
                            @if ($testimonial->author_company) , {{ $testimonial->author_company }} @endif
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Latest Jobs -->
    @if ($latestJobs->isNotEmpty())
        <section class="max-w-3xl mx-auto px-4 py-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold">Open Roles</h2>
                <a href="{{ route('careers.index') }}" class="text-sm underline text-neutral-400">View all</a>
            </div>
            <div class="space-y-3">
                @foreach ($latestJobs as $job)
                    <a href="{{ route('careers.show', $job->slug) }}"
                       class="block bg-neutral-900 border border-neutral-800 rounded-xl p-4 hover:border-neutral-700 transition">
                        <p class="font-medium">{{ $job->title }}</p>
                        <p class="text-xs text-neutral-500 mt-1">{{ $job->employment_type->label() }} · {{ $job->location ?? 'Remote' }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Latest Articles + News -->
    <section class="max-w-3xl mx-auto px-4 py-16 grid sm:grid-cols-2 gap-8">
        @if ($latestArticles->isNotEmpty())
            <div>
                <h2 class="text-xl font-semibold mb-4">From the Blog</h2>
                <div class="space-y-3">
                    @foreach ($latestArticles as $article)
                        <a href="{{ route('articles.show', $article->slug) }}" class="block hover:text-neutral-300">
                            <p class="text-sm font-medium">{{ $article->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($latestNews->isNotEmpty())
            <div>
                <h2 class="text-xl font-semibold mb-4">Company News</h2>
                <div class="space-y-3">
                    @foreach ($latestNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="block hover:text-neutral-300">
                            <p class="text-sm font-medium">{{ $news->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- CTA -->
    <section class="max-w-2xl mx-auto text-center px-4 py-24">
        <h2 class="text-3xl font-semibold">Let's build something together.</h2>
        <p class="text-neutral-400 mt-3">Have a project in mind, or want to join the team?</p>
        <a href="{{ route('careers.index') }}" class="inline-block bg-white text-neutral-950 font-medium rounded-lg px-6 py-3 mt-6 hover:bg-neutral-200 transition">
            Get in Touch
        </a>
    </section>
</body>
</html>