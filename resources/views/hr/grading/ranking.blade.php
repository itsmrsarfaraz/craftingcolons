<x-layouts.app :title="'Grading — '.$jobPosting->title">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Candidate Ranking</h1>
            <p class="text-ink-400 text-sm">{{ $jobPosting->title }}</p>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-ink-900 border border-ink-800 rounded-2xl divide-y divide-ink-800">
            @forelse ($attempts as $index => $attempt)
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <span class="text-ink-500 font-mono w-6">#{{ $index + 1 }}</span>
                        <div>
                            <p class="font-medium">{{ $attempt->candidate->name }}</p>
                            <p class="text-xs text-ink-500">{{ $attempt->status->label() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @if ($attempt->needsManualReview())
                            <span class="text-xs text-amber-400 uppercase tracking-wide">Pending Review</span>
                        @elseif (! is_null($attempt->score))
                            <span class="text-sm font-mono">{{ $attempt->score }}%</span>
                            <span class="text-xs px-2 py-1 rounded-full {{ $attempt->passed ? 'bg-emerald-950 text-emerald-400' : 'bg-red-950 text-red-400' }}">
                                {{ $attempt->passed ? 'Pass' : 'Fail' }}
                            </span>
                        @endif
                        <a href="{{ route('hr.grading.show', $attempt) }}" class="text-sm underline">Review</a>
                    </div>
                </div>
            @empty
                <p class="text-ink-500 text-sm px-6 py-4">No attempts yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>