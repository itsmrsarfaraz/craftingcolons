<x-layouts.app :title="'Announcements — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-4">
        <h1 class="text-2xl font-semibold">Announcements</h1>

        @forelse ($announcements as $announcement)
            <div class="card border border-ink-800 rounded-2xl p-6">
                <p class="font-medium">{{ $announcement->title }}</p>
                <p class="text-xs text-ink-500 mt-1">{{ $announcement->published_at->diffForHumans() }}</p>
                <p class="text-sm text-ink-400 mt-3">{{ $announcement->body }}</p>
            </div>
        @empty
            <p class="text-ink-500 text-sm">No announcements yet.</p>
        @endforelse
    </div>
</x-layouts.app>