<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>New Assessment — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">New Assessment</h1>
            <p class="text-neutral-400 text-sm mt-1">For: {{ $jobPosting->title }}</p>
        </div>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.assessments.store', $jobPosting) }}"
              class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $jobPosting->title.' Assessment') }}" required
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Instructions</label>
                <textarea name="instructions" rows="3"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('instructions') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Passing Marks (%)</label>
                    <input type="number" name="passing_marks" value="{{ old('passing_marks', 70) }}" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-neutral-300">
                <input type="checkbox" name="shuffle_questions" value="1" checked
                    class="rounded border-neutral-700 bg-neutral-800">
                Shuffle question order per candidate
            </label>

            <button type="submit"
                class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                Create & Add Questions
            </button>
        </form>
    </div>
</body>
</html>