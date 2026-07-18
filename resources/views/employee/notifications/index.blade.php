<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Notifications — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-4">
        <h1 class="text-2xl font-semibold">Notifications</h1>

        @foreach ($notifications as $notification)
            <div class="bg-neutral-900 border {{ $notification->read_at ? 'border-neutral-800' : 'border-neutral-600' }} rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <p class="text-xs text-neutral-500 mt-1">{{ $notification->data['excerpt'] ?? '' }}</p>
                </div>
                @if (! $notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf @method('PATCH')
                        <button class="text-xs underline text-neutral-400">Mark read</button>
                    </form>
                @endif
            </div>
        @endforeach

        {{ $notifications->links() }}
    </div>
</body>
</html>