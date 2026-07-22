<x-layouts.app :title="'Onboard '.$application->applicant->name">
    <div class="mx-auto max-w-xl">
        <h1 class="font-display text-2xl font-semibold text-white">Onboard {{ $application->applicant->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">{{ $application->jobPosting->title }}</p>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.onboarding.store', $application) }}" class="card mt-6 space-y-4 p-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="department" value="{{ old('department', $application->jobPosting->department) }}" placeholder="Department"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="designation" value="{{ old('designation', $application->jobPosting->title) }}" placeholder="Designation"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <select name="employment_type" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                @foreach (\App\Enums\EmploymentType::cases() as $type)
                    <option value="{{ $type->value }}" {{ $application->jobPosting->employment_type === $type ? 'selected' : '' }}>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>

            <select name="reports_to" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <option value="">— None —</option>
                @foreach (\App\Models\User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['employee', 'team-lead', 'hr', 'admin']))->get() as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <input type="date" name="joined_at" value="{{ old('joined_at', now()->toDateString()) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <button type="submit" class="btn-primary">Onboard Employee</button>
        </form>
    </div>
</x-layouts.app>