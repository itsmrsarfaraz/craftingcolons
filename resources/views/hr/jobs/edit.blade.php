<x-layouts.site :title="'Edit: '.$jobPosting->title">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Edit Job Posting</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.jobs.update', $jobPosting) }}" class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ old('title', $jobPosting->title) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="department" value="{{ old('department', $jobPosting->department) }}"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <select name="employment_type" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach (\App\Enums\EmploymentType::cases() as $type)
                        <option value="{{ $type->value }}" {{ $jobPosting->employment_type === $type ? 'selected' : '' }}>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>

            <input type="text" name="location" value="{{ old('location', $jobPosting->location) }}"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <textarea name="description" rows="4" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('description', $jobPosting->description) }}</textarea>

            <textarea name="responsibilities" rows="3"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('responsibilities', $jobPosting->responsibilities) }}</textarea>

            <textarea name="requirements" rows="3"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('requirements', $jobPosting->requirements) }}</textarea>

            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="salary_min" value="{{ old('salary_min', $jobPosting->salary_min) }}"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="number" name="salary_max" value="{{ old('salary_max', $jobPosting->salary_max) }}"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <input type="date" name="deadline" value="{{ old('deadline', $jobPosting->deadline?->format('Y-m-d')) }}"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <label class="flex items-center gap-2 text-sm text-ink-300">
                <input type="checkbox" name="assessment_required" value="1" {{ old('assessment_required', $jobPosting->assessment_required) ? 'checked' : '' }}
                    class="rounded border-ink-700 bg-ink-800">
                Requires assessment before shortlisting
            </label>

            <input type="number" name="passing_marks" value="{{ old('passing_marks', $jobPosting->passing_marks ?? 70) }}" min="0" max="100"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</x-layouts.site>