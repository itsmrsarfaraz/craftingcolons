<x-layouts.app :title="'Edit: '.$employee->user->name">
    <div class="mx-auto max-w-xl">
        <h1 class="font-display text-2xl font-semibold text-white">{{ $employee->user->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">{{ $employee->employee_code }} · Joined {{ $employee->joined_at->format('M j, Y') }}</p>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.employees.update', $employee) }}" class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="department" value="{{ old('department', $employee->department) }}" placeholder="Department"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="designation" value="{{ old('designation', $employee->designation) }}" placeholder="Designation"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Reports To</label>
                <select name="reports_to" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    <option value="">— None —</option>
                    @foreach (\App\Models\User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['employee', 'team-lead', 'hr', 'admin']))->where('id', '!=', $employee->user_id)->get() as $manager)
                        <option value="{{ $manager->id }}" {{ old('reports_to', $employee->reports_to) == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Status</label>
                <select name="status" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach (\App\Enums\EmployeeStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $employee->status === $status ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </div>
</x-layouts.app>