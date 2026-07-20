<x-layouts.app :title="'Applications — '.$jobPosting->title">
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $attempt->candidate->name }}</h1>
            <p class="text-ink-400 text-sm">{{ $attempt->assessment->title }} · {{ $attempt->status->label() }}</p>
        </div>

        <div class="bg-ink-900 border border-ink-800 rounded-2xl p-6 grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-ink-500">IP Address:</span> {{ $attempt->ip_address }}</div>
            <div><span class="text-ink-500">Started:</span> {{ $attempt->started_at->format('M j, Y g:i A') }}</div>
            <div><span class="text-ink-500">Submitted:</span> {{ $attempt->submitted_at?->format('M j, Y g:i A') ?? '—' }}</div>
            <div><span class="text-ink-500">Violations:</span> {{ $attempt->violation_count }} / {{ $attempt->max_violations_allowed }}</div>
        </div>

        <div class="bg-ink-900 border border-ink-800 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-3">Violation Log</h2>
            @forelse ($attempt->violations as $violation)
                <div class="flex items-center justify-between py-2 border-b border-ink-800 last:border-0 text-sm">
                    <span>{{ $violation->type->label() }}</span>
                    <span class="text-ink-500">{{ $violation->occurred_at->format('g:i:s A') }}</span>
                </div>
            @empty
                <p class="text-ink-500 text-sm">No violations recorded.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>