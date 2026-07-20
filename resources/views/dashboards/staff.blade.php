<x-layouts.app :title="'Staff Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <h1 class="font-display text-2xl font-semibold text-white">Content Overview</h1>
        <p class="mt-1 text-sm text-ink-400">Signed in as {{ auth()->user()->name }}</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('staff.articles.index') }}" class="card card-hover p-6">
                <span class="text-2xl">📰</span>
                <p class="mt-3 font-semibold text-white">Articles</p>
            </a>
            <a href="{{ route('staff.news.index') }}" class="card card-hover p-6">
                <span class="text-2xl">📢</span>
                <p class="mt-3 font-semibold text-white">News</p>
            </a>
            <a href="{{ route('staff.events.index') }}" class="card card-hover p-6">
                <span class="text-2xl">📅</span>
                <p class="mt-3 font-semibold text-white">Events</p>
            </a>
            <a href="{{ route('staff.projects.index') }}" class="card card-hover p-6">
                <span class="text-2xl">🗂️</span>
                <p class="mt-3 font-semibold text-white">Portfolio</p>
            </a>
            <a href="{{ route('staff.services.index') }}" class="card card-hover p-6">
                <span class="text-2xl">🛠️</span>
                <p class="mt-3 font-semibold text-white">Services</p>
            </a>
        </div>
    </div>
</x-layouts.app>