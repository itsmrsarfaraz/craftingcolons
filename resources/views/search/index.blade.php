<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Search{{ $query ? ": {$query}" : '' }} — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-8">
        <form method="GET" action="{{ route('search.index') }}">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search jobs, articles, projects, events, news..."
                class="w-full rounded-lg bg-neutral-900 border border-neutral-800 text-white px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-white/20">
        </form>

        @if ($query && $results->isEmpty())
            <p class="text-neutral-500">No results for "{{ $query }}".</p>
        @endif

        @foreach ($results as $type => $items)
            <div>
                <h2 class="text-sm uppercase tracking-wide text-neutral-500 mb-3">{{ ucfirst($type) }}</h2>
                <div class="space-y-2">
                    @foreach ($items as $item)
                        <a href="{{ $item['url'] }}"
                           class="block bg-neutral-900 border border-neutral-800 rounded-xl p-4 hover:border-neutral-700 transition">
                            <p class="font-medium">{{ $item['title'] }}</p>
                            <p class="text-sm text-neutral-400 mt-1">{{ $item['excerpt'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>