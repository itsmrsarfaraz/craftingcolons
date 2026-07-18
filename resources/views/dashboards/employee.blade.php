<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Employee Dashboard — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Welcome, {{ auth()->user()->name }}</h1>
                @if ($employee)
                    <p class="text-neutral-400 text-sm mt-1">
                        {{ $employee->designation }} · {{ $employee->department }} · {{ $employee->employee_code }}
                    </p>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm underline text-neutral-400">Logout</button>
            </form>
        </div>

        @if ($employee)
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-neutral-500">Employment Type</span><br>{{ $employee->employment_type->label() }}</div>
                <div><span class="text-neutral-500">Status</span><br>{{ $employee->status->label() }}</div>
                <div><span class="text-neutral-500">Joined</span><br>{{ $employee->joined_at->format('M j, Y') }}</div>
                <div><span class="text-neutral-500">Manager</span><br>{{ $employee->manager?->name ?? '—' }}</div>
            </div>
        @else
            <p class="text-neutral-500">Your employee profile hasn't been set up yet — contact HR.</p>
        @endif
    </div>
</body>
</html>