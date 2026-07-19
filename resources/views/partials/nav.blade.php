<header class="sticky top-0 z-40 border-b border-ink-800 bg-ink-950/80 backdrop-blur">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4 sm:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 font-display text-sm font-bold text-ink-950">CC</span>
            <span class="font-display text-lg font-semibold text-white">Crafting Colons</span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('projects.index') }}" class="text-sm text-ink-300 transition hover:text-white">Work</a>
            <a href="{{ route('careers.index') }}" class="text-sm text-ink-300 transition hover:text-white">Careers</a>
            <a href="{{ route('articles.index') }}" class="text-sm text-ink-300 transition hover:text-white">Articles</a>
            <a href="{{ route('news.index') }}" class="text-sm text-ink-300 transition hover:text-white">News</a>
            <a href="{{ route('events.index') }}" class="text-sm text-ink-300 transition hover:text-white">Events</a>
        </div>

        <div class="flex items-center gap-3">
            <x-global-search />
            @auth
                <a href="{{ route('applicant.dashboard') }}" class="btn-secondary !px-4 !py-2 text-xs">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-ink-300 transition hover:text-white">Sign in</a>
                <a href="{{ route('careers.index') }}" class="btn-primary !px-4 !py-2 text-xs">View Roles</a>
            @endauth
        </div>
    </nav>
</header>