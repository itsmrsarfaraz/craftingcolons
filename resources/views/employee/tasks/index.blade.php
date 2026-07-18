<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>My Tasks — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">My Tasks</h1>

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

        <form method="POST" action="{{ route('employee.tasks.store') }}"
              class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-3">
            @csrf
            <input type="text" name="title" placeholder="New task title" required
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            <textarea name="description" placeholder="Description (optional)" rows="2"
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2"></textarea>
            <div class="flex items-center gap-3">
                <input type="date" name="due_date"
                    class="rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                <button type="submit"
                    class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-neutral-200">
                    Add Task
                </button>
            </div>
        </form>

        <div class="space-y-3">
            @foreach ($tasks as $task)
                <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6" x-data="{ open: false }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $task->title }}</p>
                            <p class="text-xs text-neutral-500">
                                {{ $task->due_date?->format('M j, Y') ?? 'No due date' }}
                            </p>
                        </div>
                        <span class="text-xs uppercase tracking-wide bg-neutral-800 rounded-full px-3 py-1">
                            {{ $task->status->label() }}
                        </span>
                    </div>

                    @if ($task->description)
                        <p class="text-sm text-neutral-400 mt-2">{{ $task->description }}</p>
                    @endif

                    <div class="flex gap-2 mt-4">
                        @foreach ($task->status->allowedNextStatuses() as $next)
                            <form method="POST" action="{{ route('employee.tasks.status', $task) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $next->value }}">
                                <button class="text-xs border border-neutral-700 rounded-lg px-3 py-1 hover:bg-neutral-800">
                                    Move to {{ $next->label() }}
                                </button>
                            </form>
                        @endforeach
                        <button @click="open = !open" class="text-xs underline text-neutral-400">
                            Daily Report
                        </button>
                    </div>

                    <div x-show="open" x-cloak class="mt-4 pt-4 border-t border-neutral-800">
                        <form method="POST" action="{{ route('employee.tasks.reports.store', $task) }}" enctype="multipart/form-data" class="space-y-2">
                            @csrf
                            <textarea name="summary" placeholder="What did you do today?" rows="2" required
                                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2 text-sm"></textarea>
                            <input type="file" name="evidence" class="text-xs text-neutral-400">
                            <button type="submit" class="text-xs bg-white text-neutral-950 rounded-lg px-3 py-1.5">
                                Submit
                            </button>
                        </form>

                        @foreach ($task->reports as $report)
                            <div class="text-xs text-neutral-500 mt-3 pt-3 border-t border-neutral-800/60">
                                <span class="text-neutral-400">{{ $report->report_date->format('M j') }}:</span>
                                {{ $report->summary }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{ $tasks->links() }}
    </div>
</body>
</html>