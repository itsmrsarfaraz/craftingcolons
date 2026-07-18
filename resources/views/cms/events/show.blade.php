<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $event->seoTitle() }} — Crafting Colons</title>
    <meta name="description" content="{{ $event->seoDescription() }}">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $event->title }}</h1>
            <p class="text-sm text-neutral-400 mt-2">
                {{ $event->starts_at->format('M j, Y g:i A') }} – {{ $event->ends_at->format('g:i A') }}
                · {{ $event->is_virtual ? 'Virtual' : $event->location }}
            </p>
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

        <div class="prose prose-invert max-w-none">
            <p>{{ $event->description }}</p>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 flex items-center justify-between">
            <p class="text-sm text-neutral-400">
                {{ $event->registrations_count }}{{ $event->max_attendees ? ' / '.$event->max_attendees : '' }} registered
            </p>

            @auth
                @if ($event->isUpcoming() && ! $event->isFull())
                    <form method="POST" action="{{ route('events.register', $event->slug) }}">
                        @csrf
                        <button class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-neutral-200">
                            Register
                        </button>
                    </form>
                @elseif ($event->isFull())
                    <span class="text-sm text-red-400">Event Full</span>
                @endif
            @else
                <a href="{{ route('login') }}" class="text-sm underline">Log in to register</a>
            @endauth
        </div>
    </div>
</body>
</html>