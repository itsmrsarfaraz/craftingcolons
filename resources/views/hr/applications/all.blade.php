<x-layouts.app :title="'All Applications — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <h1 class="font-display text-2xl font-semibold text-white">All Applications</h1>
        <p class="mt-1 text-sm text-ink-400">Every applicant, across every open role.</p>

        <div class="card mt-6 divide-y divide-ink-800">
            @forelse ($applications as $application)
                <a href="{{ route('hr.applications.show', $application) }}" class="flex items-center justify-between px-6 py-4 hover:bg-ink-800/40">
                    <div>
                        <p class="font-medium text-white">{{ $application->applicant->name }}</p>
                        <p class="text-xs text-ink-500">
                            {{ $application->jobPosting->title }} · Applied {{ $application->applied_at->diffForHumans() }}
                            @if ($application->attempt && ! is_null($application->attempt->score))
                                · {{ $application->attempt->score }}%
                            @endif
                        </p>
                    </div>
                    <span class="rounded-full border border-ink-700 px-3 py-1 text-xs text-ink-300">{{ $application->status->label() }}</span>
                </a>
            @empty
                <p class="px-6 py-4 text-sm text-ink-500">No applications yet.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $applications->links() }}</div>
    </div>
</x-layouts.app>