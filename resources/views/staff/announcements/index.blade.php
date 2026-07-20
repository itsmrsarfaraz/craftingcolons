<x-layouts.app :title="'Articles — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">Announcements</h1>

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

        <form method="POST" action="{{ route('staff.announcements.store') }}"
              class="bg-ink-900 border border-ink-800 rounded-2xl p-6 space-y-4">
            @csrf
            <input type="text" name="title" placeholder="Title" required
                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
            <textarea name="body" placeholder="Announcement body" rows="4" required
                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2"></textarea>

            <div class="flex items-center gap-4">
                <select name="audience" class="rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                    @foreach (\App\Enums\AnnouncementAudience::cases() as $audience)
                        <option value="{{ $audience->value }}">{{ $audience->label() }}</option>
                    @endforeach
                </select>

                <label class="flex items-center gap-2 text-sm text-ink-300">
                    <input type="checkbox" name="publish_now" value="1" class="rounded border-ink-700 bg-ink-800">
                    Publish immediately
                </label>
            </div>

            <button type="submit"
                class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 hover:bg-ink-200 transition">
                Save Announcement
            </button>
        </form>

        <div class="space-y-3">
            @foreach ($announcements as $announcement)
                <div class="bg-ink-900 border border-ink-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <p class="font-medium">{{ $announcement->title }}</p>
                        @if ($announcement->isPublished())
                            <span class="text-xs text-emerald-400">Published</span>
                        @else
                            <form method="POST" action="{{ route('staff.announcements.publish', $announcement) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs underline">Publish Now</button>
                            </form>
                        @endif
                    </div>
                    <p class="text-sm text-ink-400 mt-2">{{ Str::limit($announcement->body, 150) }}</p>
                    <p class="text-xs text-ink-500 mt-2">Audience: {{ $announcement->audience->label() }}</p>
                </div>
            @endforeach
        </div>

        {{ $announcements->links() }}
    </div>
</x-layouts.app>