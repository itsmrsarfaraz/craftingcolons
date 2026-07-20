<x-layouts.app :title="'Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-3xl">
        <h1 class="font-display text-2xl font-semibold text-white">Welcome, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">Your internship tasks and progress.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('employee.tasks.index') }}" class="card card-hover p-6">
                <span class="text-2xl">✅</span>
                <p class="mt-3 font-semibold text-white">My Tasks</p>
                <p class="mt-1 text-sm text-ink-400">View and update assigned tasks.</p>
            </a>
            <a href="{{ route('announcements.feed') }}" class="card card-hover p-6">
                <span class="text-2xl">📣</span>
                <p class="mt-3 font-semibold text-white">Announcements</p>
                <p class="mt-1 text-sm text-ink-400">Company updates that matter to you.</p>
            </a>
        </div>
    </div>
</x-layouts.app>