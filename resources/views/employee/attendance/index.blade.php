<x-layouts.app :title="'Attendance — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">Attendance</h1>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card rounded-2xl p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-ink-400">Today</p>
                @if ($today)
                    <p class="text-sm mt-1">
                        In: {{ $today->clock_in?->format('g:i A') ?? '—' }} ·
                        Out: {{ $today->clock_out?->format('g:i A') ?? '—' }} ·
                        <span class="uppercase text-xs">{{ $today->status->label() }}</span>
                    </p>
                @else
                    <p class="text-sm mt-1 text-ink-500">Not clocked in yet.</p>
                @endif
            </div>
            <div class="flex gap-2">
                @if (! $today)
                    <form method="POST" action="{{ route('employee.attendance.clock-in') }}">
                        @csrf
                        <button class="btn-primary font-medium rounded-lg px-4 py-2 text-sm hover:bg-ink-200">
                            Clock In
                        </button>
                    </form>
                @elseif (! $today->clock_out)
                    <form method="POST" action="{{ route('employee.attendance.clock-out') }}">
                        @csrf
                        <button class="btn-primary rounded-lg px-4 py-2 text-sm hover:bg-ink-800">
                            Clock Out
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card rounded-2xl divide-y divide-ink-800">
            @foreach ($attendances as $attendance)
                <div class="flex items-center justify-between px-6 py-3 text-sm">
                    <span>{{ $attendance->date->format('M j, Y') }}</span>
                    <span class="text-ink-400">
                        {{ $attendance->clock_in?->format('g:i A') ?? '—' }} – {{ $attendance->clock_out?->format('g:i A') ?? '—' }}
                    </span>
                    <span class="uppercase text-xs">{{ $attendance->status->label() }}</span>
                </div>
            @endforeach
        </div>

        {{ $attendances->links() }}
    </div>
</x-layouts.app>