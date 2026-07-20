<x-layouts.app :title="'Notifications — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-4">
        <h1 class="text-2xl font-semibold">Notifications</h1>

        @foreach ($notifications as $notification)
            <div class="bg-ink-900 border {{ $notification->read_at ? 'border-ink-800' : 'border-ink-600' }} rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <p class="text-xs text-ink-500 mt-1">{{ $notification->data['excerpt'] ?? '' }}</p>
                </div>
                @if (! $notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf @method('PATCH')
                        <button class="text-xs underline text-ink-400">Mark read</button>
                    </form>
                @endif
            </div>
        @endforeach

        {{ $notifications->links() }}
    </div>
</x-layouts.app>