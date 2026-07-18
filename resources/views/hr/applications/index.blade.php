<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Applications — {{ $jobPosting->title }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Applications</h1>
            <p class="text-neutral-400 text-sm">{{ $jobPosting->title }}</p>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl divide-y divide-neutral-800">
            @forelse ($applications as $application)
                <a href="{{ route('hr.applications.show', $application) }}"
                   class="flex items-center justify-between px-6 py-4 hover:bg-neutral-800/40 transition">
                    <div>
                        <p class="font-medium">{{ $application->applicant->name }}</p>
                        <p class="text-xs text-neutral-500">
                            Applied {{ $application->applied_at->diffForHumans() }}
                            @if ($application->attempt)
                                · {{ $application->attempt->status->label() }}
                                @if (! is_null($application->attempt->score))
                                    · {{ $application->attempt->score }}%
                                @endif
                            @endif
                        </p>
                    </div>
                    <span class="text-xs uppercase tracking-wide bg-neutral-800 rounded-full px-3 py-1">
                        {{ $application->status->label() }}
                    </span>
                </a>
            @empty
                <p class="text-neutral-500 text-sm px-6 py-4">No applications yet.</p>
            @endforelse
        </div>

        {{ $applications->links() }}
    </div>
</body>
</html>