<x-layouts.app :title="'New Assessment — Crafting Colons'">
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-2xl font-semibold text-white">New Assessment</h1>
        <p class="mt-1 text-sm text-ink-400">For: {{ $jobPosting->title }}</p>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.assessments.store', $jobPosting) }}" class="card mt-6 space-y-4 p-6">
            @csrf

            <div>
                <label class="mb-1 block text-sm text-ink-300">Title</label>
                <input type="text" name="title" value="{{ old('title', $jobPosting->title.' Assessment') }}" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Instructions</label>
                <textarea name="instructions" rows="3"
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('instructions') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-sm text-ink-300">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required
                        class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm text-ink-300">Passing Marks (%)</label>
                    <input type="number" name="passing_marks" value="{{ old('passing_marks', 70) }}" required
                        class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Max Violations Allowed</label>
                <input type="number" name="max_violations_allowed" value="{{ old('max_violations_allowed', $defaultMaxViolations ?? 3) }}" min="1" max="20" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <label class="flex items-center gap-2 text-sm text-ink-300">
                <input type="checkbox" name="shuffle_questions" value="1" checked
                    class="rounded border-ink-700 bg-ink-800">
                Shuffle question order per candidate
            </label>

            <button type="submit" class="btn-primary">Create &amp; Add Questions</button>
        </form>
    </div>
</x-layouts.app>