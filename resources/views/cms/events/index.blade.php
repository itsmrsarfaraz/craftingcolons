<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Events — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-3xl font-semibold">Events</h1>

        <div class="grid gap-4">
            @foreach ($events as $event)
                <a href="{{ route('events.show', $event->slug) }}"
                   class="block bg-neutral-900 border border-neutral-800 rounded-2xl p-6 hover:border-neutral-700 transition">
                    <h2 class="text-lg font-semibold">{{ $event->title }}</h2>
                    <p class="text-sm text-neutral-400 mt-2">
                        {{ $event->starts_at->format('M j, Y g:i A') }} · {{ $event->is_virtual ? 'Virtual' : $event->location }}
                    </p>
                </a>
            @endforeach
        </div>

        {{ $events->links() }}
    </div>
</body>
</html>