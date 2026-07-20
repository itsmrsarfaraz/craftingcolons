<x-layouts.app :title="'My Applications — Crafting Colons'">
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-2xl font-semibold text-white">My Applications</h1>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="card mt-6 divide-y divide-ink-800">
            @forelse ($applications as $application)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="font-medium text-white">{{ $application->jobPosting->title }}</p>
                        <p class="text-xs text-ink-500">Applied {{ $application->applied_at->diffForHumans() }}</p>
                    </div>
                    <span class="rounded-full bg-ink-800 px-3 py-1 text-xs uppercase tracking-wide text-ink-300">
                        {{ $application->status->label() }}
                    </span>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-ink-500">You haven't applied to any positions yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>