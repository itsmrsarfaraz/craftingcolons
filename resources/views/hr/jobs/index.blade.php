<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Manage Jobs — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Job Postings</h1>
            <a href="{{ route('hr.jobs.create') }}"
               class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-neutral-200">
                + New Posting
            </a>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @foreach ($postings as $posting)
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium">{{ $posting->title }}</p>
                        <p class="text-xs text-neutral-400">{{ $posting->status->label() }} · {{ $posting->applications()->count() }} applicants</p>
                    </div>
                    <div class="flex gap-2">
                        @if ($posting->status->value !== 'published')
                            <form method="POST" action="{{ route('hr.jobs.publish', $posting) }}">
                                @csrf @method('PATCH')
                                <button class="text-sm underline text-emerald-400">Publish</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('hr.jobs.close', $posting) }}">
                                @csrf @method('PATCH')
                                <button class="text-sm underline text-red-400">Close</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{ $postings->links() }}
    </div>
</body>
</html>