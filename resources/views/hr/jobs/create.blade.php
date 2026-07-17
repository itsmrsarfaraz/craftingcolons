<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>New Job Posting — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">New Job Posting</h1>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.jobs.store') }}"
              class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Employment Type</label>
                    <select name="employment_type" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                        @foreach (\App\Enums\EmploymentType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Responsibilities</label>
                <textarea name="responsibilities" rows="3"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('responsibilities') }}</textarea>
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Requirements</label>
                <textarea name="requirements" rows="3"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('requirements') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Salary Min</label>
                    <input type="number" name="salary_min" value="{{ old('salary_min') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Salary Max</label>
                    <input type="number" name="salary_max" value="{{ old('salary_max') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Application Deadline</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <label class="flex items-center gap-2 text-sm text-neutral-300">
                <input type="checkbox" name="assessment_required" value="1" {{ old('assessment_required') ? 'checked' : '' }}
                    class="rounded border-neutral-700 bg-neutral-800">
                Requires assessment before shortlisting
            </label>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Passing Marks (if assessment required)</label>
                <input type="number" name="passing_marks" value="{{ old('passing_marks', 70) }}" min="0" max="100"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <button type="submit"
                class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                Create Posting
            </button>
        </form>
    </div>
</body>
</html>