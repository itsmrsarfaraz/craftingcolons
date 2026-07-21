<footer class="border-t border-ink-800">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-16 sm:px-8 sm:grid-cols-4">
        <div class="sm:col-span-2">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 p-1.5 text-white">
                    <x-logo-mark class="h-full w-full" />
                </span>
                <span class="font-display text-lg font-semibold text-white">Crafting Colons</span>
            </div>
            <p class="mt-4 max-w-sm text-sm text-ink-400">
                Software development and IT services company based in Islamabad, Pakistan — building products, training talent, and shipping real work.
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-ink-500">Company</p>
            <div class="mt-4 flex flex-col gap-2 text-sm text-ink-400">
                <a href="{{ route('projects.index') }}" class="hover:text-white">Our Work</a>
                <a href="{{ route('careers.index') }}" class="hover:text-white">Careers</a>
                <a href="{{ route('events.index') }}" class="hover:text-white">Events</a>
            </div>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-ink-500">Resources</p>
            <div class="mt-4 flex flex-col gap-2 text-sm text-ink-400">
                <a href="{{ route('articles.index') }}" class="hover:text-white">Articles</a>
                <a href="{{ route('news.index') }}" class="hover:text-white">News</a>
                <a href="{{ route('sitemap') }}" class="hover:text-white">Sitemap</a>
            </div>
        </div>
    </div>

    <div class="border-t border-ink-800 py-6">
        <p class="text-center text-xs text-ink-500">© {{ now()->year }} Crafting Colons. All rights reserved.</p>
    </div>
</footer>