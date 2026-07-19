<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Activity Log — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">Activity Log</h1>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl divide-y divide-neutral-800">
            @foreach ($logs as $log)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm">{{ $log->description }}</p>
                        <span class="text-xs text-neutral-500">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-neutral-500 mt-1">
                        {{ $log->user?->name ?? 'System' }}
                        @if ($log->ip_address) · {{ $log->ip_address }} @endif
                    </p>
                    @if ($log->changes)
                        <p class="text-xs text-neutral-600 mt-1 font-mono">
                            {{ $log->changes['from'] ?? '' }} → {{ $log->changes['to'] ?? '' }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{ $logs->links() }}
    </div>
</body>
</html>