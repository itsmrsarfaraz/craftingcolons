<x-layouts.app :title="'Team Lead Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-3xl">
        <h1 class="font-display text-2xl font-semibold text-white">Welcome, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">Review your team's work.</p>

        <div class="mt-8">
            <a href="{{ route('team-lead.tasks.review') }}" class="card card-hover p-6">
                <span class="text-2xl">🔍</span>
                <p class="mt-3 font-semibold text-white">Tasks Awaiting Review</p>
                <p class="mt-1 text-sm text-ink-400">Approve or request changes on submitted work.</p>
            </a>
        </div>
    </div>
</x-layouts.app>