<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>My Applications — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">My Applications</h1>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($applications as $application)
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium">{{ $application->jobPosting->title }}</p>
                        <p class="text-xs text-neutral-400">Applied {{ $application->applied_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs uppercase tracking-wide bg-neutral-800 rounded-full px-3 py-1">
                        {{ $application->status->label() }}
                    </span>
                </div>
            @empty
                <p class="text-neutral-500">You haven't applied to any positions yet.</p>
            @endforelse
        </div>
    </div>
</body>
</html>