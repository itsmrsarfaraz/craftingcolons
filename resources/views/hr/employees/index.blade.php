<x-layouts.app :title="'Employees — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <h1 class="font-display text-2xl font-semibold text-white">Employees</h1>
        <p class="mt-1 text-sm text-ink-400">Everyone currently onboarded at Crafting Colons.</p>

        <form method="GET" class="mt-6 flex flex-wrap gap-3">
            <select name="department" onchange="this.form.submit()" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-sm text-white">
                <option value="">All departments</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-sm text-white">
                <option value="">All statuses</option>
                @foreach (\App\Enums\EmployeeStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>
        </form>

        <div class="card mt-6 divide-y divide-ink-800">
            @forelse ($employees as $employee)
                <a href="{{ route('hr.employees.edit', $employee) }}" class="flex items-center justify-between px-6 py-4 hover:bg-ink-800/40">
                    <div>
                        <p class="font-medium text-white">{{ $employee->user->name }}</p>
                        <p class="text-xs text-ink-500">
                            {{ $employee->employee_code }} · {{ $employee->designation }} · {{ $employee->department }}
                            @if ($employee->manager) · Reports to {{ $employee->manager->name }} @endif
                        </p>
                    </div>
                    <span class="rounded-full border border-ink-700 px-3 py-1 text-xs
                        {{ $employee->status->value === 'active' ? 'text-mint-400' : ($employee->status->value === 'terminated' ? 'text-red-400' : 'text-ink-300') }}">
                        {{ $employee->status->label() }}
                    </span>
                </a>
            @empty
                <p class="px-6 py-4 text-sm text-ink-500">No employees match these filters.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $employees->links() }}</div>
    </div>
</x-layouts.app>