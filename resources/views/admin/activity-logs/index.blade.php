<x-layouts.app :title="'Logs — Crafting Colons'">
    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">Activity Log</h1>

        <div class="card border border-ink-800 rounded-2xl divide-y divide-ink-800">
            @foreach ($logs as $log)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm">{{ $log->description }}</p>
                        <span class="text-xs text-ink-500">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-ink-500 mt-1">
                        {{ $log->user?->name ?? 'System' }}
                        @if ($log->ip_address) · {{ $log->ip_address }} @endif
                    </p>
                    @if ($log->changes)
                        <p class="text-xs text-ink-600 mt-1 font-mono">
                            {{ $log->changes['from'] ?? '' }} → {{ $log->changes['to'] ?? '' }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{ $logs->links() }}
    </div>
</x-layouts.app>