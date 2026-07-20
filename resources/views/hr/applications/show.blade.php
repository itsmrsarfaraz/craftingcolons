<x-layouts.app :title="$application->applicant->name.' — Application'">
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-2xl font-semibold text-white">{{ $application->applicant->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">{{ $application->jobPosting->title }}</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card mt-6 space-y-3 p-6 text-sm">
            <div class="flex justify-between"><span class="text-ink-500">Email</span><span class="text-white">{{ $application->applicant->email }}</span></div>
            <div class="flex justify-between"><span class="text-ink-500">Phone</span><span class="text-white">{{ $application->applicant->applicantProfile?->phone ?? '—' }}</span></div>
            <div class="flex justify-between">
                <span class="text-ink-500">CV</span>
                @if ($application->document)
                    <a href="{{ route('applicant.documents.download', $application->document) }}" class="text-brand-400 hover:underline">{{ $application->document->original_name }}</a>
                @else
                    <span class="text-white">—</span>
                @endif
            </div>
            @if ($application->cover_letter)
                <div>
                    <span class="mb-1 block text-ink-500">Cover Letter</span>
                    <p class="whitespace-pre-wrap rounded-lg bg-ink-800/60 p-3 text-ink-200">{{ $application->cover_letter }}</p>
                </div>
            @endif
        </div>

        @if ($application->attempt)
            <div class="card mt-6 space-y-2 p-6 text-sm">
                <h2 class="mb-2 text-lg font-semibold text-white">Assessment</h2>
                <div class="flex justify-between"><span class="text-ink-500">Status</span><span class="text-white">{{ $application->attempt->status->label() }}</span></div>
                <div class="flex justify-between"><span class="text-ink-500">Score</span><span class="text-white">{{ $application->attempt->score ?? '—' }}{{ $application->attempt->score !== null ? '%' : '' }}</span></div>
                <div class="flex justify-between"><span class="text-ink-500">Violations</span><span class="text-white">{{ $application->attempt->violation_count }}</span></div>
                <a href="{{ route('hr.grading.show', $application->attempt) }}" class="text-xs text-brand-400 hover:underline">View / Grade Attempt</a>
            </div>
        @endif

        <div class="card mt-6 p-6">
            <h2 class="mb-3 text-lg font-semibold text-white">Status: {{ $application->status->label() }}</h2>

            @if (! empty($application->status->allowedNextStatuses()))
                <form method="POST" action="{{ route('hr.applications.status', $application) }}" class="flex flex-wrap gap-2">
                    @csrf
                    @method('PATCH')
                    @foreach ($application->status->allowedNextStatuses() as $next)
                        <button type="submit" name="status" value="{{ $next->value }}"
                            class="rounded-lg border px-4 py-2 text-sm transition {{ $next->value === 'rejected' ? 'border-red-900 text-red-400 hover:bg-red-950/40' : 'border-ink-700 text-ink-200 hover:bg-ink-800' }}">
                            Move to {{ $next->label() }}
                        </button>
                    @endforeach
                </form>

                @if ($application->status->value === 'offered')
                    <a href="{{ route('hr.onboarding.create', $application) }}" class="btn-primary mt-4 inline-block">
                        Proceed to Onboarding
                    </a>
                @endif
            @else
                <p class="text-sm text-ink-500">This application is in a final state.</p>
                @if ($application->status->value === 'hired' && ! $application->applicant->employee)
                    <a href="{{ route('hr.onboarding.create', $application) }}" class="btn-primary mt-4 inline-block">
                        Onboard as Employee
                    </a>
                @endif
            @endif
        </div>
    </div>
</x-layouts.app>