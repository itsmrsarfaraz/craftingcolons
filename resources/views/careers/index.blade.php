<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Careers — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-8">
        <div>
            <h1 class="text-3xl font-semibold">Open Positions</h1>
            <p class="text-neutral-400 mt-1">Join Crafting Colons and build with us.</p>
        </div>

        <div class="space-y-4">
            @forelse ($jobs as $job)
                <a href="{{ route('careers.show', $job->slug) }}"
                   class="block bg-neutral-900 border border-neutral-800 rounded-2xl p-6 hover:border-neutral-700 transition">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold">{{ $job->title }}</h2>
                        <span class="text-xs uppercase tracking-wide text-neutral-400 bg-neutral-800 rounded-full px-3 py-1">
                            {{ $job->employment_type->label() }}
                        </span>
                    </div>
                    <p class="text-neutral-400 text-sm mt-2">{{ $job->department }} · {{ $job->location ?? 'Remote' }}</p>
                </a>
            @empty
                <p class="text-neutral-500">No open positions right now. Check back soon.</p>
            @endforelse
        </div>

        {{ $jobs->links() }}
    </div>
</body>
</html>