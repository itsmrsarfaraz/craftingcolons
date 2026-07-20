<x-layouts.app :title="'Admin Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-5xl">
        <h1 class="font-display text-2xl font-semibold text-white">Admin Overview</h1>
        <p class="mt-1 text-sm text-ink-400">Signed in as {{ auth()->user()->name }}</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('hr.jobs.index') }}" class="card card-hover p-6">
                <span class="text-2xl">💼</span>
                <p class="mt-3 font-semibold text-white">Job Postings</p>
                <p class="mt-1 text-sm text-ink-400">Manage recruitment listings.</p>
            </a>
            <a href="{{ route('staff.articles.index') }}" class="card card-hover p-6">
                <span class="text-2xl">📰</span>
                <p class="mt-3 font-semibold text-white">Content</p>
                <p class="mt-1 text-sm text-ink-400">Articles, news, events, portfolio.</p>
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" class="card card-hover p-6">
                <span class="text-2xl">📊</span>
                <p class="mt-3 font-semibold text-white">Activity Log</p>
                <p class="mt-1 text-sm text-ink-400">Audit trail of key actions.</p>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="card card-hover p-6">
                <span class="text-2xl">⚙️</span>
                <p class="mt-3 font-semibold text-white">Settings</p>
                <p class="mt-1 text-sm text-ink-400">Configure app-wide defaults.</p>
            </a>
        </div>
    </div>
</x-layouts.app>