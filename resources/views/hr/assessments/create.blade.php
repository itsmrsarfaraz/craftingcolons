<x-layouts.app :title="'Assessment — '.$jobPosting->title">
    <div class="max-w-xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">New Assessment</h1>
            <p class="text-ink-400 text-sm mt-1">For: {{ $jobPosting->title }}</p>
        </div>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.assessments.store', $jobPosting) }}"
              class="bg-ink-900 border border-ink-800 rounded-2xl p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-ink-300 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $jobPosting->title.' Assessment') }}" required
                    class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
            </div>

            <div>
                <label class="block text-sm text-ink-300 mb-1">Instructions</label>
                <textarea name="instructions" rows="3"
                    class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">{{ old('instructions') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-ink-300 mb-1">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-ink-300 mb-1">Max Violations</label>
                    <input type="number" name="max_violations_allowed" value="{{ old('max_violations_allowed', $defaultMaxViolations) }}" min="1" max="20"
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-ink-300 mb-1">Passing Marks (%)</label>
                    <input type="number" name="passing_marks" value="{{ old('passing_marks', 70) }}" required
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-ink-300">
                <input type="checkbox" name="shuffle_questions" value="1" checked
                    class="rounded border-ink-700 bg-ink-800">
                Shuffle question order per candidate
            </label>

            <button type="submit"
                class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 hover:bg-ink-200 transition">
                Create & Add Questions
            </button>
        </form>
    </div>
</x-layouts.app>