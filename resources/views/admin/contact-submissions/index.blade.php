<x-layouts.app :title="'Messages — Crafting Colons'">
    <div class="mx-auto max-w-3xl">
        <h1 class="font-display text-2xl font-semibold text-white">Contact & Newsletter Submissions</h1>

        <div class="card mt-6 divide-y divide-ink-800">
            @forelse ($submissions as $submission)
                <div class="flex items-center justify-between px-6 py-4 {{ $submission->is_read ? '' : 'bg-brand-500/5' }}">
                    <div>
                        <p class="font-medium text-white">{{ $submission->name ?? $submission->email }}</p>
                        <p class="text-xs text-ink-500">
                            {{ $submission->type->label() }} · {{ $submission->email }} · {{ $submission->created_at->diffForHumans() }}
                        </p>
                        @if ($submission->message)
                            <p class="mt-2 text-sm text-ink-300">{{ Str::limit($submission->message, 150) }}</p>
                        @endif
                    </div>
                    @unless ($submission->is_read)
                        <form method="POST" action="{{ route('admin.contact-submissions.read', $submission) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs text-brand-400 hover:underline">Mark Read</button>
                        </form>
                    @endunless
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-ink-500">No submissions yet.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $submissions->links() }}</div>
    </div>
</x-layouts.app>