<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $application->applicant->name }} — Application</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $application->applicant->name }}</h1>
            <p class="text-neutral-400 text-sm">{{ $application->jobPosting->title }}</p>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-neutral-500">Email</span><span>{{ $application->applicant->email }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-500">Phone</span><span>{{ $application->applicant->applicantProfile?->phone ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-500">CV</span>
                @if ($application->document)
                    <a href="{{ route('applicant.documents.download', $application->document) }}" class="underline">{{ $application->document->original_name }}</a>
                @else
                    <span>—</span>
                @endif
            </div>
            @if ($application->cover_letter)
                <div>
                    <span class="text-neutral-500 block mb-1">Cover Letter</span>
                    <p class="bg-neutral-800/60 rounded-lg p-3 whitespace-pre-wrap">{{ $application->cover_letter }}</p>
                </div>
            @endif
        </div>

        @if ($application->attempt)
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-2 text-sm">
                <h2 class="text-lg font-semibold mb-2">Assessment</h2>
                <div class="flex justify-between"><span class="text-neutral-500">Status</span><span>{{ $application->attempt->status->label() }}</span></div>
                <div class="flex justify-between"><span class="text-neutral-500">Score</span><span>{{ $application->attempt->score ?? '—' }}{{ $application->attempt->score !== null ? '%' : '' }}</span></div>
                <div class="flex justify-between"><span class="text-neutral-500">Violations</span><span>{{ $application->attempt->violation_count }}</span></div>
                <a href="{{ route('hr.grading.show', $application->attempt) }}" class="underline text-xs">View / Grade Attempt</a>
            </div>
        @endif

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-3">Status: {{ $application->status->label() }}</h2>

            @if (! empty($application->status->allowedNextStatuses()))
                <form method="POST" action="{{ route('hr.applications.status', $application) }}" class="flex gap-2 flex-wrap">
                    @csrf
                    @method('PATCH')
                    @foreach ($application->status->allowedNextStatuses() as $next)
                        <button type="submit" name="status" value="{{ $next->value }}"
                            class="text-sm rounded-lg px-4 py-2 border {{ $next->value === 'rejected' ? 'border-red-900 text-red-400 hover:bg-red-950/40' : 'border-neutral-700 hover:bg-neutral-800' }} transition">
                            Move to {{ $next->label() }}
                        </button>
                    @endforeach
                </form>
            @else
                <p class="text-neutral-500 text-sm">This application is in a final state.</p>
            @endif
        </div>
    </div>
</body>
</html>