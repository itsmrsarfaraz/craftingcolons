<x-layouts.app :title="'Attempt Review — Crafting Colons'">
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-2xl font-semibold text-white">{{ $attempt->candidate->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">{{ $attempt->assessment->title }} · {{ $attempt->status->label() }}</p>

        <div class="card mt-6 grid grid-cols-2 gap-4 p-6 text-sm">
            <div><span class="text-ink-500">IP Address:</span> <span class="text-white">{{ $attempt->ip_address }}</span></div>
            <div><span class="text-ink-500">Started:</span> <span class="text-white">{{ $attempt->started_at->format('M j, Y g:i A') }}</span></div>
            <div><span class="text-ink-500">Submitted:</span> <span class="text-white">{{ $attempt->submitted_at?->format('M j, Y g:i A') ?? '—' }}</span></div>
            <div><span class="text-ink-500">Violations:</span> <span class="text-white">{{ $attempt->violation_count }} / {{ $attempt->max_violations_allowed }}</span></div>
        </div>

        <div class="card mt-6 p-6">
            <h2 class="mb-3 text-lg font-semibold text-white">Violation Log</h2>
            @forelse ($attempt->violations as $violation)
                <div class="flex items-center justify-between border-b border-ink-800 py-2 text-sm last:border-0">
                    <span class="text-white">{{ $violation->type->label() }}</span>
                    <span class="text-ink-500">{{ $violation->occurred_at->format('g:i:s A') }}</span>
                </div>
            @empty
                <p class="text-sm text-ink-500">No violations recorded.</p>
            @endforelse
        </div>

        <a href="{{ route('hr.grading.show', $attempt) }}" class="btn-primary mt-6 inline-block">Grade This Attempt</a>
    </div>
</x-layouts.app>