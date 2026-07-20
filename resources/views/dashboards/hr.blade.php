<x-layouts.app :title="'HR Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <h1 class="font-display text-2xl font-semibold text-white">HR Overview</h1>
        <p class="mt-1 text-sm text-ink-400">Signed in as {{ auth()->user()->name }}</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('hr.jobs.index') }}" class="card card-hover p-6">
                <span class="text-2xl">💼</span>
                <p class="mt-3 font-semibold text-white">Job Postings</p>
                <p class="mt-1 text-sm text-ink-400">Create and manage open roles.</p>
            </a>
            <a href="{{ route('hr.jobs.create') }}" class="card card-hover p-6">
                <span class="text-2xl">➕</span>
                <p class="mt-3 font-semibold text-white">Post a New Job</p>
                <p class="mt-1 text-sm text-ink-400">Start a fresh recruitment listing.</p>
            </a>
        </div>
    </div>
</x-layouts.app>