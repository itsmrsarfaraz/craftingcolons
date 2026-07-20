<x-layouts.app :title="'Job Postings — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-semibold text-white">Job Postings</h1>
            <a href="{{ route('hr.jobs.create') }}" class="btn-primary !px-4 !py-2 text-sm">+ New Posting</a>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="card mt-6 divide-y divide-ink-800">
            @foreach ($postings as $posting)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <a href="{{ route('hr.jobs.edit', $posting) }}" class="font-medium text-white hover:text-brand-400">{{ $posting->title }}</a>
                        <p class="text-xs text-ink-500">{{ $posting->status->label() }} · {{ $posting->applications()->count() }} applicants</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('hr.jobs.edit', $posting) }}" class="text-sm text-ink-400 hover:underline">Edit</a>
                        @if ($posting->status->value !== 'published')
                            <form method="POST" action="{{ route('hr.jobs.publish', $posting) }}">
                                @csrf @method('PATCH')
                                <button class="text-sm text-emerald-400 hover:underline">Publish</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('hr.jobs.close', $posting) }}">
                                @csrf @method('PATCH')
                                <button class="text-sm text-red-400 hover:underline">Close</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $postings->links() }}</div>
    </div>
</x-layouts.app>