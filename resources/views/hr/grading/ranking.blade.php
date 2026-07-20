<x-layouts.app :title="'Ranking — '.$jobPosting->title">
    <div class="mx-auto max-w-3xl">
        <h1 class="font-display text-2xl font-semibold text-white">Candidate Ranking</h1>
        <p class="mt-1 text-sm text-ink-400">{{ $jobPosting->title }}</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="card mt-6 divide-y divide-ink-800">
            @forelse ($attempts as $index => $attempt)
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <span class="w-6 font-mono text-ink-500">#{{ $index + 1 }}</span>
                        <div>
                            <p class="font-medium text-white">{{ $attempt->candidate->name }}</p>
                            <p class="text-xs text-ink-500">{{ $attempt->status->label() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @if ($attempt->needsManualReview())
                            <span class="text-xs uppercase tracking-wide text-amber-400">Pending Review</span>
                        @elseif (! is_null($attempt->score))
                            <span class="font-mono text-sm text-white">{{ $attempt->score }}%</span>
                            <span class="rounded-full px-2 py-1 text-xs {{ $attempt->passed ? 'bg-emerald-950 text-emerald-400' : 'bg-red-950 text-red-400' }}">
                                {{ $attempt->passed ? 'Pass' : 'Fail' }}
                            </span>
                        @endif
                        <a href="{{ route('hr.grading.show', $attempt) }}" class="text-sm text-brand-400 hover:underline">Review</a>
                    </div>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-ink-500">No attempts yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>