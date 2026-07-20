<x-layouts.app :title="'Onbording — '.$jobPosting->title">
    <div class="max-w-xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Onboard {{ $application->applicant->name }}</h1>
            <p class="text-ink-400 text-sm mt-1">{{ $application->jobPosting->title }}</p>
        </div>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.onboarding.store', $application) }}"
              class="bg-ink-900 border border-ink-800 rounded-2xl p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-ink-300 mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department', $application->jobPosting->department) }}"
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-ink-300 mb-1">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $application->jobPosting->title) }}"
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm text-ink-300 mb-1">Employment Type</label>
                <select name="employment_type" class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                    @foreach (\App\Enums\EmploymentType::cases() as $type)
                        <option value="{{ $type->value }}" {{ $application->jobPosting->employment_type === $type ? 'selected' : '' }}>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-ink-300 mb-1">Reports To (optional)</label>
                <select name="reports_to" class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                    <option value="">— None —</option>
                    @foreach (\App\Models\User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['employee', 'team-lead', 'hr', 'admin']))->get() as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-ink-300 mb-1">Joining Date</label>
                <input type="date" name="joined_at" value="{{ old('joined_at', now()->toDateString()) }}" required
                    class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
            </div>

            <button type="submit"
                class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 hover:bg-ink-200 transition">
                Onboard Employee
            </button>
        </form>
    </div>
</x-layouts.app>