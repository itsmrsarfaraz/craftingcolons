<x-layouts.app :title="'Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <h1 class="font-display text-2xl font-semibold text-white">Welcome back, {{ auth()->user()->name }}</h1>

        @if ($employee)
            <p class="mt-1 text-sm text-ink-400">
                {{ $employee->designation }} · {{ $employee->department }} · {{ $employee->employee_code }}
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="card p-5">
                    <p class="text-xs text-ink-500">Employment Type</p>
                    <p class="mt-1 font-semibold text-white">{{ $employee->employment_type->label() }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-ink-500">Status</p>
                    <p class="mt-1 font-semibold text-white">{{ $employee->status->label() }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-ink-500">Joined</p>
                    <p class="mt-1 font-semibold text-white">{{ $employee->joined_at->format('M j, Y') }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-ink-500">Manager</p>
                    <p class="mt-1 font-semibold text-white">{{ $employee->manager?->name ?? '—' }}</p>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <a href="{{ route('employee.attendance.index') }}" class="card card-hover p-6">
                    <span class="text-2xl">⏱️</span>
                    <p class="mt-3 font-semibold text-white">Attendance</p>
                    <p class="mt-1 text-sm text-ink-400">Clock in and view your history.</p>
                </a>
                <a href="{{ route('employee.tasks.index') }}" class="card card-hover p-6">
                    <span class="text-2xl">✅</span>
                    <p class="mt-3 font-semibold text-white">Tasks</p>
                    <p class="mt-1 text-sm text-ink-400">Manage your assigned work.</p>
                </a>
            </div>
        @else
            <div class="card mt-6 p-8 text-center text-ink-400">
                Your employee profile hasn't been set up yet — contact HR.
            </div>
        @endif
    </div>
</x-layouts.app>