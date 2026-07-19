<header
    x-data="{ mobileOpen: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8)"
    class="sticky top-0 z-50 border-b transition-colors duration-300"
    :class="scrolled ? 'border-ink-800 bg-ink-950/90 backdrop-blur' : 'border-transparent bg-transparent'"
>
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 font-display text-sm font-bold text-ink-950">CC</span>
            <span class="font-display text-base font-semibold text-white sm:text-lg">Crafting Colons</span>
        </a>

        <!-- Desktop links -->
        <div class="hidden items-center gap-7 lg:flex">
            <a href="{{ route('projects.index') }}" class="text-sm text-ink-300 transition hover:text-white">Work</a>
            <a href="{{ route('careers.index') }}" class="text-sm text-ink-300 transition hover:text-white">Careers</a>
            <a href="{{ route('articles.index') }}" class="text-sm text-ink-300 transition hover:text-white">Articles</a>
            <a href="{{ route('news.index') }}" class="text-sm text-ink-300 transition hover:text-white">News</a>
            <a href="{{ route('events.index') }}" class="text-sm text-ink-300 transition hover:text-white">Events</a>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <div class="hidden sm:block">
                <x-global-search />
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                @auth
                    <a href="{{ route('applicant.dashboard') }}" class="btn-secondary !px-4 !py-2 text-xs">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-ink-300 transition hover:text-white">Sign in</a>
                    <a href="{{ route('careers.index') }}" class="btn-primary !px-4 !py-2 text-xs">View Roles</a>
                @endauth
            </div>

            <!-- Hamburger — mobile/tablet only -->
            <button
                @click="mobileOpen = !mobileOpen"
                class="flex h-10 w-10 items-center justify-center rounded-lg text-white lg:hidden"
                aria-label="Toggle menu"
            >
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile drawer -->
    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-ink-800 bg-ink-950 px-4 py-6 lg:hidden"
    >
        <div class="mb-5">
            <x-global-search />
        </div>
        <div class="flex flex-col gap-1">
            <a href="{{ route('projects.index') }}" class="rounded-lg px-3 py-3 text-sm font-medium text-ink-200 hover:bg-ink-900 hover:text-white">Work</a>
            <a href="{{ route('careers.index') }}" class="rounded-lg px-3 py-3 text-sm font-medium text-ink-200 hover:bg-ink-900 hover:text-white">Careers</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-3 py-3 text-sm font-medium text-ink-200 hover:bg-ink-900 hover:text-white">Articles</a>
            <a href="{{ route('news.index') }}" class="rounded-lg px-3 py-3 text-sm font-medium text-ink-200 hover:bg-ink-900 hover:text-white">News</a>
            <a href="{{ route('events.index') }}" class="rounded-lg px-3 py-3 text-sm font-medium text-ink-200 hover:bg-ink-900 hover:text-white">Events</a>
        </div>
        <div class="mt-5 flex flex-col gap-3 border-t border-ink-800 pt-5">
            @auth
                <a href="{{ route('applicant.dashboard') }}" class="btn-secondary w-full">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-secondary w-full">Sign in</a>
                <a href="{{ route('careers.index') }}" class="btn-primary w-full">View Roles</a>
            @endauth
        </div>
    </div>
</header>